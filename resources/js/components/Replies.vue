<template>
    <div>
        <div v-for="(item, index) in items">
            <reply :data="item" @deleted="remove(index)"></reply>
        </div>
    </div>
</template>

<script>
    import Reply from '../components/Reply.vue';

    export default {
        name: "replies",
        components : { Reply },
        props : [ 'data' ],
        data() {
            return {
                items : this.data
            }
        },
        methods: {
            remove(index) {
                this.items.splice(index, 1);
                this.$emit('remove');
                flash('Reply was deleted!');
            }
        }
    }
</script>
