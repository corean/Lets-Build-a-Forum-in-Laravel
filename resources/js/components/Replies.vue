<template>
    <div>
        <div v-for="(item, index) in items" :key="item.id">
            <reply :data="item" @deleted="remove(index)"></reply>
        </div>

        <paginator :data-set="dataSet" @changed="fetch"></paginator>

        <new-reply @created="add"></new-reply>
    </div>
</template>

<script>
    import newReply from './newReply.vue';
    import Reply from './Reply.vue';
    import collection from '../mixins/collection.js';

    export default {
        name: "replies",
        components: {Reply, newReply},
        mixins: [collection],
        data() {
            return {
                dataSet: false,
            }
        },
        created() {
            // let page = location.search.match(/page=(\d+)/)[1];
            this.fetch();
        },
        methods: {
            fetch(page) {
                // console.log(page);
                axios.get(this.url(page)).then(this.refresh);
            },
            url(page) {
                if (!page) {
                    let query = location.search.match(/page=(\d+)/);
                    page = query ? query[1] : 1;
                }
                return `${location.pathname}/replies?page=${page}`;
            },
            refresh({data}) {
                this.dataSet = data;
                this.items = data.data;
            },

        }
    }
</script>
