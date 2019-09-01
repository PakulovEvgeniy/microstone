<template>
    <div v-title="title">
      <div class="manuf-page__content">
          <div class="manuf-page__left">
              <div class="left-manuf">
                  <div class="toc-index">
                      <h4>Производители</h4>
                  </div>
              </div>
          </div>
          <div class="manuf-page__list"></div>
      </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    export default {
        data() {
            return {
                title: 'Производители'
            }
        },
        computed: {
          ...mapGetters([
          ])
        },
        methods: {
            
        },
        components: {
        },
        beforeRouteEnter (to, from, next) {
          next(vm => {
            vm.$store.commit('setNonVisibleAside', true);
          })
        }
    }
</script>

<style>
  .manuf-page__left {
    height: 100%;
    margin-right: 20px;
  }
  .manuf-page__list {
    max-width: 902px;
    width: 100%;
  }
  .manuf-page__content {
      display: flex;
      margin-top: 10px;
  }
  .left-manuf {
    position: relative;
    border-radius: 8px;
    box-shadow: 0 1px 2px 0 rgba(0,0,0,0.16);
    border: solid 1px transparent;
    background: #fff;
  }
  .toc-index {
    line-height: 1.5em;
    margin: 0 0 2em;
  }
  .toc-index h4 {
    font-size: 1.3em;
    color: #444;
    text-align: center;
    font-weight: normal;
  }
  @media (min-width: 1200px) {
    .manuf-page__left {
      width: 278px;
      margin-right: 20px;
    }
    .manuf-page__list {
        width: calc(100% - 278px - 20px);
    }
  }
</style>