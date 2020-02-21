<template>
    <div class="search-module">
       <div class="search-module__container">
         <div class="wrap">
           <div class="search-module__content">
             <ul class="tabs">
               <li @click="curPage=0" class="found" :class="{current: curPage==0}">Поиск по наименованию</li>
               <li @click="curPage=1" class="found-res" :class="{current: curPage==1}">Экcпресс поиск резисторов и конденсаторов</li>
             </ul>
             <a @click="$emit('close')" class="close">закрыть</a>
             <section v-show="curPage==0" class="search-module__search-name">
               <form @submit.prevent="onSubmit">
                 <input type="text" placeholder="введите наименование" autofocus="autofocus" autocomplete="off">
                 <button type="submit" class="ico-search">&nbsp;</button>
                 <span class="total-text"></span>
               </form>
             </section>
             <section v-show="curPage==1" class="search-module__search-res"></section>
           </div>
         </div>
       </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
              curPage: 0
            }
        }, 
        props: [
      
        ],
        methods: {
          onSubmit() {

          }
        }
    }
</script>

<style lang="less">
@import '../../../less/vars.less';
  .search-module {
    position: absolute;
    width: 100%;
    bottom: 20px;
    &__content {
      .tabs {
        position: absolute;
        top: -2px;
        left: 0;
        width: 100%;
        background-color: @main-color;
        border-bottom: 2px solid @main-color;
        padding: 0;
        box-sizing: border-box;
        font-size: 14px;
        margin: 0 0 2em;
        li {
          background-repeat: no-repeat;
          display: inline-block;
          line-height: 14px;
          text-align: center;
          vertical-align: bottom;
          background-position: 10px 50%;
          cursor: pointer;
          border-top-right-radius: 5px;
          border-top-left-radius: 5px;
          margin-bottom: -2px;
          border: 2px solid transparent;
          color: #fff;
          font-size: 13px;
          padding: 8px 15px 6px 26px;
          &.current {
            background-color: #fff;
            border: 2px solid @main-color;
            border-bottom: 2px solid #fff;
            color: #333;
          }
          &:hover {
            background-color: #edf2f8;
            border: 2px solid @main-color;
            border-bottom: 2px solid #edf2f8;
            color: #333;
          }
          &:first-child {
            border-left: none;
          }
          &.found {
            background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAHNJREFUeNpi+P//PwMQT/iPCiZAxeEYpugDECcAsQMQF0D5E9AV/odKIptQABWHizExQMABBlRwAUorwARgCh3QFBpA6QdwESQ3gqwzgNJfgHgzuhux+XozlE5AV4gNJyArxqcQWXEAI1g1fhAAxB8AAgwA8uXwwGtw3isAAAAASUVORK5CYII=");
          }
          &.found-res {
            background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAKCAYAAABmBXS+AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAExJREFUeNqMUFsKACAIc9H9z1AnXdgDhlS4H1HnpoKkCTyBxIliCTip7UmG3qk1cPnhY8e0nQIh5hevYVG7KTupCwGX6zrkmU+lIcAAPBoaigBMNUUAAAAASUVORK5CYII=");
          }
          &.found.current, &.found:hover {
            background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAItJREFUeNpiYQCCWWdT+oFUAQMCTEgznlOIxGdggipKAOJEIHYEYpCCBKg4HLBATSoEmrAAKnYAqAhE90M1QUyESTKgggtQJymgK3RAU2gAIoC2PEC2egIQ10OtOwDVVA/EG5B1MuLwNUhRAMiDMLczMuAAQM2gkJgPU4xTIZriQLwKoYpBTvgAEGAAs4EuDnOrcc8AAAAASUVORK5CYII=");
          }
          &.found-res.current, &.found-res:hover {
            background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAKCAYAAABmBXS+AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAElJREFUeNpiYEACs86m/EemYYCJgQjAAtT1HkgLoEsgmfYBxWhs1oHYRFmH4Uhs8sQ5HJ+JacZzGEE0I7LvYILovmMkxiSAAAMAaIcsn5IXWjAAAAAASUVORK5CYII=");
          }
        }
      }
      .close {
        top: 0;
        bottom: auto;
        right: 4px;
        padding: 0 30px 0 3px;
        color: #fff;
        position: absolute;
        font-size: 14px;
        height: 26px;
        line-height: 26px;
        display: inline-block;
        background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAX5JREFUeNrElrFxhDAQRU8wJE6IrwFnTrALgBwXcBW4ADdAAy7AFVCAKUABoWEudgMXkzhhNHh3LDS6tVbIZw5rZgdGLPvQ35WW3W6jIZYcpmnK4YJ2D7Ynj09gHZgUQsiLQADAwJUjODcQWgGwCwYB5BkuhwtVqgH2sggCCK6i/GNKGoBV9kTkWEm5Qu5LHesnSOfkTK5xHGO0paiM30HHPJcOJt/sxOOLbdve4X2WZR9pmn66IMMw3PR9f4v3RVEcaYGAhI9mRbqEndWllIoxEAbkIOjDLHavYxvpcuqRJInClcRxrFwwG4I+6MvAciMdlY37ajsgneOkneWbQe++ZFPYLGkA5LsQhHiIQmoVA9ky/gbi3EfXHEEgKh1XICGgUygE5fJVI3PYGlAXCsGc0JwtwDobJF3Hiq+EXTAGJA1INy2nfL7qsmGePSTpWYcH4OvKxfY0N8LI2lQ4Ua8Iqe1uG5EdjJ2xWQHS0C77f61805+Ta/xubTa+BBgAiuUwJ0Bp5igAAAAASUVORK5CYII=");
        background-position: 100% 50%;
        background-repeat: no-repeat;
        &:hover {
          text-decoration: none;
          background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAc9JREFUeNrElrFKA0EQhueClfoKgaCFnWApNrEQy9hpp8FCS0UUn0ERU2ohZ6mdKcXCNGIp2FlEAnmFmFbnv73dm1t3b4+A5w+bI3ez/5fZncweUUWKQgHfu+sbfGnxaPJoWI8HPHo8utHN48NEIAbAOHaY+wRom4G90iCGXPLlwNyozxMtLRMtLOYDP96J3l6Jhn15t8OwwyCIIchixwC29n4DbAF4dy2Btwxre0G5TFbWiDb3iaZnyi3c+Ivo/oro5cmZWWTtybOBtI/UZCgEk3HxhYSt6j2rifDYLBcyweTTbTWGn34Inuk4CHPhIT01KC1hVV3YE5nBeER0fuKG4R6eIUYLc+Gh1Ei9TUYtk43eeEw4PuPrrBsmIYhBrBY8sqxaEtRMPlHCUvU5N8wFQaxU5pV4T+kUzS+xpWHaGFe9pD5I3qthF4NfdmYhiEO1qppqOZC9J74CKQEamFYSgmC5iqpRtiXhrUGq46JBhiDYE181SmVeuc7QVcb97JegMxSVsAsms8kabNeA0kNLLR+6sO5dyR+3oLokTPY9eKTLpg/E4qY6iUJNNb3RSb4gEBNkZmWOiTykI0/b/zn4Kj3KK305+YvXrcr0I8AAPxoHos1qOXAAAAAASUVORK5CYII=");
        }
      }
    }
    &__container {
      border: none;
      position: absolute;
      top: 100%;
      width: 100%;
      min-height: 126px;
      z-index: 20;
      text-align: left;
      white-space: normal;
      background: #fff;
      margin-top: 10px;
      background-color: transparent;
      .wrap {
        border-radius: 7px;
        padding: 42px 10px 10px;
        min-height: 126px;
        position: relative;
        border: 4px solid @main-color;
        background-color: #fff;
        &:before {
          position: absolute;
          z-index: 60;
          left: 24px;
          top: -24px;
          content: "";
          height: 0;
          width: 0;
          pointer-events: none;
          border: 10px solid transparent;
          border-bottom: 10px solid @main-color;
        }
      }
    }
    &__search-name {
      form {
        margin: 1em 0 2em;
        padding: 0 10px;
        white-space: nowrap;
      }
      input {
        border: none;
        border-bottom: 1px solid #1d71b8;
        padding: 3px 4px;
        width: 226px;
        margin-right: 5px;
        outline: none;
      }
      button {
        border: none;
        width: 26px;
        height: 26px;
        outline: none;
        cursor: pointer;
        background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAUBJREFUeNrkVcENwjAMbBEDsABS2YARyo8nI8AEJRNUnaB0AmAC+uRXRmCESCzQEbClixSFQJomfMCSZaWRfTknvibJX9pcXGfkWUiN9EPxnEJBvjG2WvLmUa9vwUAEcqSwdeQeCEyMBiKQmsIeS0leIbJx+0pEthOB7byB0K5OFSEXVKh3MF4NaePUWBeKieOk3LIczDjHCTQx1uriq09JYFkZOV5AiXY3LpM+r24Sac6ysUBDhjPTWil9gVrEktXAkVsaOV6vrsHl8ml5nnY2OcJepuV8fWB7zNF9rATpYO+MQWaIPEdnAmyjiyoAOoA59S/1eL69KUf0vYNC6GbVvzRwfpjtxbL1AhY0sLiTk2VrS4fYR2Pk+H9xmxeq3VEkCG0ymfEjWUbVOg1MgIliJKO2zqIcS/zTZPKz9hRgAOZBeTgMFOUuAAAAAElFTkSuQmCC") no-repeat 50% 50%;
        &:hover {
          background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAT1JREFUeNrkVdENgjAQBeMAjIAbOAJ++esIGAcAJiBMgAxgwAnk1y8cwRE6AhvoXbwmzYmU0sbEeMnlUpr29b32Hp73l/E4bAPI0GYPf2TzCEoCuWNTLWTln643ayAAqaHEmrVHAMtmAwFICSWloYAsqGKgfDlVjAbA9sZAJFcnN4HMYKNew3gzRcYlGyeSieakKFlEzHCNFmjBxvLii1EZXiwLtsYIyFPuRhfC5NUtHPVZOBdoSnOGipTCFKilmqMbaNbmbI0RUKWcthyxo1phVH2jYXvqo/tcC1LBPgWCBFSxj84A2Do3VQLoCEzrf77B8+25HcH3jhxCjUH/8y37B9leBqbewKwalu6kGZiK4RCpM0aa/xfKvJJyO7Egkokzw0eydup1ClhGTCQj4VQ67hzEREzxwN+NpwADAIXkej7JkP87AAAAAElFTkSuQmCC") no-repeat 50% 50%;
        }
      }
    }
  }
</style>