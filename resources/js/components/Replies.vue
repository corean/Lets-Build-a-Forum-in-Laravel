<template>
    <div>
        <div v-for="(item, index) in items" :key="item.id">
            <reply :data="item"  @deleted="remove(index)"></reply>
        </div>
        <new-reply :endpoint="endpoint" @created="add"></new-reply>
    </div>
</template>

<script>
    import newReply from '../components/newReply.vue';
    import Reply from '../components/Reply.vue';

    export default {
        name: "replies",
        components : { Reply, newReply },
        props : [ 'data' ],
        data() {
            return {
                items : this.data,
                endpoint: location.pathname + '/replies'
            }
        },
        methods: {
            remove(index) {
                // console.log('remove', index);
                this.items.splice(index, 1);
                this.$emit('remove');
                flash('Reply was deleted!');
            },
            add(reply) {
                // console.log('reply', reply);
                this.items.push(reply);
                this.$emit('add');
            }
        }
    }
</script>
