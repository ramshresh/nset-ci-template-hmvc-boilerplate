<template>
    <div>
        <h1>Users View</h1>
        <p>Team ID : {{$route.params.teamId}}</p>
        <button @click="goHome">Go Home</button>
    </div>
</template>

<script>
    export default {
        watch:{
            '$route'(to,from){
                alert(to.params.teamId)
            }
        },
        created(){
           console.log('created User Component')
        },
        methods:{
            goHome(){
                this.$router.push('/');
            }
        }
    }
</script>