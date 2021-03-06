<?php
namespace Gmf\Sys\Http\Resources;
use Closure;
use Gmf\Sys\Builder;
use Illuminate\Http\Resources\Json\Resource;
use Storage;

class File extends Resource {
	private $callback;

	public function withCallback(Closure $callback = null) {
		$this->callback = $callback;
		return $this;
	}
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request
	 * @return array
	 */
	public function toArray($request) {
		if (empty($this->id)) {
			return false;
		}
		$rtn = new Builder;
		Common::toField($this, $rtn, ['id', 'title', 'type', 'url', 'ext', 'created_at']);
		Common::toIntField($this, $rtn, ['size']);

		if (!empty($this->pivot)) {
			$rtn['post_id'] = $this->pivot->post_id;
			$rtn['created_at'] = $this->pivot->created_at . '';
		}
		if (empty($this->ext)) {
			$rtn['ext'] = substr(strrchr($this->title, "."), 1);
		}
		if ($this->canImage()) {
			$rtn['image_url'] = $this->getImagePathURL($request);
			$rtn['url'] = $this->getImagePathURL($request);
			$rtn['can_image'] = true;
		}
		if ($this->canPdf()) {
			$rtn['pdf_url'] = $this->getPdfPathURL($request);
			$rtn['can_pdf'] = true;
		}
		if (!is_null($this->callback)) {
			$flag = call_user_func($this->callback, $rtn, $this);
			if ($flag === 0) {
				return false;
			}
		}
		return $rtn;
	}
	private function canImage() {
		return !empty($this->type) && starts_with($this->type, 'image/');
	}
	private function canPdf() {
		if ($this->ext == 'doc' || $this->ext == 'docx'
			|| $this->ext == 'xls' || $this->ext == 'xlsx'
			|| $this->ext == 'ppt' || $this->ext == 'pptx'
			|| $this->ext == 'txt' || !empty($this->pdf_path)) {
			return true;
		}
		return false;
	}
	private function getFullPath($disk, $path) {
		$url = false;
		if (!$disk) {
			$disk = 'public';
		}
		if ($disk && $path && Storage::disk($disk)->exists($path)) {
			$url = Storage::disk($disk)->url($path);
		}
		if (!$url && file_exists(public_path() . $path)) {
			$url = $path;
		}
		if ($url && !starts_with(strtolower($url), 'http')) {
			return url($url);
		}
		return $url;
	}
	public function getImagePathURL() {
		if (!empty($this->path)) {
			return $this->getFullPath($this->disk, $this->path);
		}
		return url('/api/sys/images/' . $this->id);
	}
	private function getPdfPathURL() {
		if (!empty($this->pdf_path)) {
			return $this->getFullPath($this->pdf_disk, $this->pdf_path);
		}
		return '';
	}
}
