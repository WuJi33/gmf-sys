<template>
  <div :class="b([type, color])" :style="style">
    <span :class="b('spinner', type)">
      <i v-for="item in (type === 'spinner' ? 12 : 0)" />
      <svg v-if="type === 'circular'" :class="b('circular')" viewBox="25 25 50 50">
        <circle cx="50" cy="50" r="20" fill="none"/>
      </svg>
    </span>
  </div>
</template>

<script>
import mdBem from 'gmf/core/mixins/MdBem/MdBem';
export default {
  name: 'MdLoading2',
  props: {
    size: String,
    type: {
      type: String,
      default: 'circular'
    },
    color: {
      type: String,
      default: 'black'
    }
  },
  mixins:[mdBem],
  computed: {
    style() {
      return this.size ? {
        width: this.size,
        height: this.size
      } : {};
    }
  }
};
</script>
<style lang="scss">
.md-loading2 {
  width: 30px;
  height: 30px;
  z-index: 0;
  font-size: 0;
  line-height: 0;
  position: relative;
  vertical-align: middle;

  &--circle {
    width: 16px;
    height: 16px;
  }

  &__spinner {
    z-index: -1;
    width: 100%;
    height: 100%;
    position: relative;
    display: inline-block;
    box-sizing: border-box;
    animation: md-rotate 0.8s linear infinite;

    &--circle {
      border-radius: 100%;
      border: 3px solid transparent;
    }

    &--gradient-circle {
      background-size: contain;
    }

    &--spinner {
      animation-timing-function: steps(12);

      i {
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        position: absolute;

        &::before {
          width: 2px;
          height: 25%;
          content: ' ';
          display: block;
          margin: 0 auto;
          border-radius: 40%;
          background-color: currentColor;
        }
      }
    }

    &--circular {
      animation-duration: 2s;
    }
  }

  &__circular {
    width: 100%;
    height: 100%;

    circle {
      stroke-width: 3;
      stroke-linecap: round;
      animation: md-circular 1.5s ease-in-out infinite;
    }
  }

  &--black {
    .md-loading2__spinner--circle {
      border-color: #c9c9c9;
      border-top-color: #c9c9c9;
    }

    .md-loading2__spinner--gradient-circle {
      background-image: url('https://img.yzcdn.cn/vant/gradient-circle-black.png');
    }

    .md-loading2__spinner--spinner {
      color: #c9c9c9;
    }

    circle {
      stroke: #c9c9c9;
    }
  }

  &--white {
    .md-loading2__spinner--circle {
      border-color: rgba(0, 0, 0, .1);
      border-top-color: rgba(255, 255, 255, .7);
    }

    .md-loading2__spinner--gradient-circle {
      background-image: url('https://img.yzcdn.cn/vant/gradient-circle-white.png');
    }

    .md-loading2__spinner--spinner {
      color: #fff;
    }

    circle {
      stroke:#fff;
    }
  }
}

@keyframes md-circular {
  0% {
    stroke-dasharray: 1, 200;
    stroke-dashoffset: 0;
  }
  50% {
    stroke-dasharray: 90, 150;
    stroke-dashoffset: -40;
  }
  100% {
    stroke-dasharray: 90, 150;
    stroke-dashoffset: -120;
  }
}

@for $i from 1 to 12 {
  .md-loading2__spinner--spinner i:nth-of-type($i) {
    opacity: calc(1 - (0.75 / 12) * ($i - 1));
    transform: rotate(calc($i * 30deg));
  }
}

</style>
