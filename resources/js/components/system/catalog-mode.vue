<template>
    <div class="catalog-mode">
      <input type="radio" id="mode-simple" class="hidden" name="mode" value="simple" :checked="value=='simple'" @input="$emit('input', $event.target.value)">
      <label for="mode-simple"><i class="fas fa-th-list"></i></label>
      <input type="radio" id="mode-tile" class="hidden" name="mode" value="tile" :checked="value=='tile'" @input="$emit('input', $event.target.value)">
      <label for="mode-tile"><i class="fas fa-th-large"></i></label>
    </div>
</template>

<script>

    export default {
        data() {
            return {
            }
        },
        props: [
          'value'
        ]
    }
</script>

<style>
  .catalog-mode {
    margin: 0;
  }
  .catalog-mode input[type=radio]+label {
    color: #8c8c8c;
    padding-top: 5px;
  }
  .catalog-mode label {
    font-size: 22px;
    cursor: pointer;
  }
  .top-filters__view-mode .catalog-mode input[type=radio]:checked+label {
    color: #333;
  }
</style>