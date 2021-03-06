<template>
  <div
    :class="['md-ripple', rippleClasses]"
    @touchstart.passive.stop="touchStartCheck"
    @touchmove.passive.stop="touchMoveCheck"
    @touchend.passive.stop="clearWave"
    @mousedown.passive.stop="startRipple"
    @mouseup.passive.stop="clearWave">
    <slot />

    <transition-group name="md-ripple" v-if="!isDisabled">
      <span v-for="(ripple, index) in ripples" :key="'ripple'+index" :class="['md-ripple-wave', waveClasses]" :style="ripple.waveStyles" />
    </transition-group>
  </div>
</template>

<script>
  import raf from 'raf'
  import MdComponent from 'gmf/core/MdComponent'
  import debounce from 'gmf/core/utils/MdDebounce'

  export default new MdComponent({
    name: 'MdRipple',
    props: {
      mdActive: null,
      mdDisabled: Boolean,
      mdCentered: Boolean
    },
    data: () => ({
      ripples: [],
      touchTimeout: null,
      eventType: null
    }),
    computed: {
      isDisabled () {
        return !this.$material.ripple || this.mdDisabled
      },
      rippleClasses () {
        return {
          'md-disabled': this.isDisabled
        }
      },
      waveClasses () {
        return {
          'md-centered': this.mdCentered
        }
      }
    },
    watch: {
      mdActive (active) {
        const isBoolean = typeof active === 'boolean'
        const isEvent = active.constructor.name.toLowerCase() === 'mouseevent'

        if (isBoolean && this.mdCentered && active) {
          this.startRipple({
            type: 'mousedown'
          })
          this.$emit('update:mdActive', false)
        } else if (isEvent) {
          this.startRipple(active)
          this.$emit('update:mdActive', false)
        }
        this.clearWave()
      }
    },
    methods: {
      touchMoveCheck () {
        window.clearTimeout(this.touchTimeout)
      },
      touchStartCheck ($event) {
        this.touchTimeout = window.setTimeout(() => {
          this.startRipple($event)
        }, 100)
      },
      startRipple ($event) {
        raf(() => {
          const { eventType, isDisabled, mdCentered } = this

          if (!isDisabled && (!eventType || eventType === $event.type)) {
            let size = this.getSize()
            let position = null

            if (mdCentered) {
              position = this.getCenteredPosition(size)
            } else {
              position = this.getHitPosition($event, size)
            }

            this.eventType = $event.type
            this.ripples.push({
              animating: true,
              waveStyles: this.applyStyles(position, size)
            })
          }
        })
      },
      applyStyles (position, size) {
        size += 'px'

        return {
          ...position,
          width: size,
          height: size
        }
      },
      clearWave: debounce(function () {
        this.ripples = []
      }, 2000),
      getSize () {
        const { offsetWidth, offsetHeight } = this.$el

        return Math.round(Math.max(offsetWidth, offsetHeight))
      },
      getCenteredPosition (size) {
        const halfSize = -size / 2 + 'px'

        return {
          'margin-top': halfSize,
          'margin-left': halfSize
        }
      },
      getHitPosition ($event, elementSize) {
        const rect = this.$el.getBoundingClientRect()
        let top = $event.pageY
        let left = $event.pageX

        if ($event.type === 'touchstart') {
          top = $event.changedTouches[0].pageY
          left = $event.changedTouches[0].pageX
        }

        return {
          top: top - rect.top - elementSize / 2 - document.documentElement.scrollTop + 'px',
          left: left - rect.left - elementSize / 2 - document.documentElement.scrollLeft + 'px'
        }
      }
    }
  })
</script>

<style lang="scss">
  @import "~gmf/components/MdAnimation/variables";

  .md-ripple {
    width: 100%;
    height: 100%;
    position: relative;
    z-index: 10;
    overflow: hidden;
    -webkit-mask-image: radial-gradient(circle, #fff 100%, #000 100%);
  }

  .md-ripple-wave {
    position: absolute;
    z-index: 1;
    pointer-events: none;
    background: currentColor;
    border-radius: 50%;
    opacity: 0;
    transform: scale(2) translateZ(0);

    &.md-centered {
      animation-duration: 1.2s;
      top: 50%;
      left: 50%;
    }
    ~ *:not(.md-ripple-wave) {
      position: relative;
      z-index: 2;
    }
  }

  .md-ripple-enter-active {
    transition: .8s $md-transition-stand-timing;
    transition-property: opacity, transform;
    will-change: opacity, transform;
    &.md-centered {
      transition-duration: 1.2s;
    }
  }

  .md-ripple-enter {
    opacity: .26;
    transform: scale(.26) translateZ(0);
  }
</style>
