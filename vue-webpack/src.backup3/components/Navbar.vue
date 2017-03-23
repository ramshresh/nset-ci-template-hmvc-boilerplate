<template>
    <nav id="topNav" class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">{{app_name}}</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav pull-left">

                </ul>
            </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->

    </nav>
</template>
<script type="text/babel">
    export default{
        data(){
            return {
                app_name: 'Building Compliance Survey ',

            }
        }
    }
</script>
<style>
</style>
