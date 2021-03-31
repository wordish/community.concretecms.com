<template>

    <div>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a :class="{'nav-link': true, 'active': this.activeLink === 'locations'}" href="#"
                   @click.prevent="activeLink = 'locations'">Locations</a>
            </li>
            <li class="nav-item">
                <a :class="{'nav-link': true, 'active': this.activeLink === 'teams'}" href="#"
                   @click.prevent="activeLink = 'teams'">Teams</a>
            </li>
        </ul>
        <hr class="mt-0">

        <div class="card" v-if="activeLink === 'locations'">
            <div class="card-body">
                All registered hosting locations coming soon.
            </div>
        </div>

        <div class="card" v-if="activeLink === 'teams'">
            <div class="card-body">
                Teams management interface coming soon.
            </div>
        </div>
    </div>

</template>
<script>
    export default {
        props: {
            project: Object,
        },
        data: function () {
            return {
                activeLink: 'locations',
            }
        },
        methods: {},
        computed: {}
    }
</script>

