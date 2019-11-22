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

<style>
    .notification-container {
        display: inline-block;
    }

    .notification-container .title i {
        display: inline-block;
    }
    .notification-container .title.inactive i {
        padding: 4px;
        font-size: 10px;
        line-height: 10px;
    }
    .notification-container .title.inactive:hover i {
        color: #fff;
        opacity: 0.7;
    }
</style>
