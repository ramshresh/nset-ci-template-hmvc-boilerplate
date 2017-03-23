<template>
    <div>
        <select>
            <option v-for="district in districts">
                {{ district.label }}
            </option>
        </select>

    </div>
</template>
<script type="text/babel">
    export default{
        data(){
            return {
                districts: [{code:22,label:"Dolakha"}]
            }
        }
    }
</script>
<style>
    .wrapper {
        background-color: #ccc
    }
</style>
