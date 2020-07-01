<template>
    <li v-tooltip="'Уведомлений нет'" class="notification-container menu-dropdown">
        <a class="title inactive menu-dropdown-target">
            <i class="fa fa-bell"></i>
        </a>
        <div class="notification-block menu-dropdown-items">
            <div>ddsds</div>
        </div>
    </li>
</template>

<script>
    export default {
    }
</script>

<style lang="less">
    .notification-container {
        display: inline-block;
        .title {
            i {
                display: inline-block;
            }
            &.inactive {
                i {
                    padding: 4px;
                    font-size: 10px;
                    line-height: 10px;
                }
                &:hover i {
                    color: #fff;
                    opacity: 0.7;
                }
            }
        }
    }
</style>
