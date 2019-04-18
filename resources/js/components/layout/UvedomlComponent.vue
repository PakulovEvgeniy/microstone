<template>
    <li class="notification-container menu-dropdown">
        <a class="title inactive menu-dropdown-target">
            <i class="fas fa-bell"></i>
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
        border: solid 1px #d8d8d8;
        border-radius: 10px;
        padding: 4px;
        color: #7e7e7e;
        font-size: 10px;
        line-height: 10px;
    }
    .notification-container .title.inactive:hover i {
        color: #fff;
        background: gray;
        border-color: gray;
    }
</style>
