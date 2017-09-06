<?php

namespace Gmf\Sys\Libs;
use Closure;
use Gmf\Sys\Builder;
use Gmf\Sys\Models\Profile;

class Common {
	/**
	 * 获取参数配置信息
	 * @param  string $name 参数名称
	 * @return [type]       [description]
	 */
	public static function getProfileValue($name = '') {
		return Profile::getValue($name);
	}
	/**
	 * 取排名的前top数据，并对其它数据进行合并
	 * @param  Collection   $arrary    [description]
	 * @param  integer      $top       [description]
	 * @param  Array|array  $sumFields [description]
	 * @param  Closure|null $callback  function(Builder $b){}
	 * @return [type]                  [description]
	 */
	public static function takeOtherSum(Collection $arrary, $top = 5, Array $sumFields = [], Closure $callback = null) {

		$topData = $arrary->take($top);
		//other
		$other = $arrary->slice($top);
		if ($other->count()) {
			$b = new Builder;
			$callback && $callback($b);

			$other->each(function ($item, $key) use ($b, $sumFields) {
				foreach ($sumFields as $key => $value) {
					$b->{$value} = $b->{$value}+$item->{$value};
				}
			});
			$topData->push($b);
		}
		return $topData;
	}
	public static function EncryptDES($data, $key) {
		// $cipher_list = mcrypt_list_algorithms();//mcrypt支持的加密算法列表
		// $mode_list = mcrypt_list_modes(); //mcrypt支持的加密模式列表

		$td = mcrypt_module_open(MCRYPT_DES, "", MCRYPT_MODE_ECB, ""); //使用MCRYPT_DES算法,ecb模式
		$size = mcrypt_enc_get_iv_size($td); //设置初始向量的大小
		$iv = mcrypt_create_iv($size, MCRYPT_RAND); //创建初始向量
		$data = static::pkcs5_pad($data, $size);
		$key_size = mcrypt_enc_get_key_size($td); //返回所支持的最大的密钥长度（以字节计算）

		$salt = '';
		$subkey = substr(strtoupper(md5($key)), 0, $key_size); //对key复杂处理，并设置长度
		mcrypt_generic_init($td, $subkey, $iv);
		$rtn = mcrypt_generic($td, $data);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		$rtn = base64_encode($rtn);
		return $rtn;
	}
	public static function DecryptDES($data, $key) {
		$data = base64_decode($data);
		$td = mcrypt_module_open(MCRYPT_DES, "", MCRYPT_MODE_ECB, ""); //使用MCRYPT_DES算法,ecb模式
		$size = mcrypt_enc_get_iv_size($td); //设置初始向量的大小
		$iv = mcrypt_create_iv($size, MCRYPT_RAND); //创建初始向量
		$key_size = mcrypt_enc_get_key_size($td); //返回所支持的最大的密钥长度（以字节计算）

		$salt = '';
		$subkey = substr(strtoupper(md5($key)), 0, $key_size); //对key复杂处理，并设置长度
		mcrypt_generic_init($td, $subkey, $iv);
		$rtn = rtrim(mdecrypt_generic($td, $data));
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);

		$rtn = static::pkcs5_unpad($rtn);

		return $rtn;
	}
	private static function pkcs5_pad($text, $blocksize) {
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad);
	}

	private static function pkcs5_unpad($text) {
		$pad = ord($text{strlen($text) - 1});
		if ($pad > strlen($text)) {
			return false;
		}
		if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
			return false;
		}
		return substr($text, 0, -1 * $pad);
	}
}
