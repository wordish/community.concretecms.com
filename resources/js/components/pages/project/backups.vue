<template>
<card :loading="$apollo.loading">
    <div class="card-body">
        <h4>Backups</h4>
        <p>Backups are smart, don't be a dummy.</p>
        <table class="w-100">
            <thead>
            <tr>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</card>
</template>

<script>
import Card from "../../basic/card";
export default {
    name: "backups",
    components: {Card}
}
</script>

<style scoped>
.table td {
    opacity: 70%;
}
.table tr:nth-child(even) {
    background-color: rgba(216, 216, 216, .1);
}
</style>
