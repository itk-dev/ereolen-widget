<template>
    <div class="row topbar">
        <div class="col-3 col-sm-2 topbar-left">
            <p class="text-white mt-2 mb-2">Widgets</p>
        </div>
        <div class="col-9 col-sm-10 topbar-right">
            <nav class="navbar navbar-expand-sm mt-0 mb-0 ">
                <button class="navbar-toggler text-white float-right mt-0" type="button" data-toggle="collapse" data-target="#topbarRight" aria-controls="topbarRight" aria-expanded="false" aria-label="Toggle navigation">
                    <v-icon name="bars" />
                </button>
                <div class="collapse navbar-collapse" id="topbarRight">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">{{ $t('Build widget') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">{{ $t('How to') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">{{ $t('About widgets') }}</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'TopBar'
    }
</script>
