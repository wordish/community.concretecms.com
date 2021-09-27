<template>
    <div>
        <page-header title="Dev Info" :showUser="false"></page-header>
        <card>
            <div class="card-body">
                <h3>Translation</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Language</th>
                            <th v-for="group of groups" :key="group">{{ group }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr v-for="{lang, stats} of translations" :key="lang">
                        <td><strong>{{ lang.toUpperCase() }}</strong></td>
                        <td v-for="(total, group) in stats.coverage" :key="group"><code>{{ total }}%</code></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </card>
    </div>
</template>
<script>
import Card from "../basic/card";
import PageHeader from "../basic/header";
import EnvironmentsHeader from "./project/environments/environments-header";
export default {
    components: {PageHeader, Card},
    computed: {
        *groups() {
            const stats = this.$t.stats('en')

            for (const group in stats.total) {
                yield group
            }
        },
        *translations() {
            const requiredLanguages = ['es', 'jp', 'de'];
            for (const lang in this.$t.lang) {
                if (requiredLanguages.indexOf(lang) === -1) {
                    requiredLanguages.push(lang)
                }
            }

            for (const lang of requiredLanguages) {
                yield {lang, stats: this.$t.stats(lang)}
            }
        }
    }
}
</script>