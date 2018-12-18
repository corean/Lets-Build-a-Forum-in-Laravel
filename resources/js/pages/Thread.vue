<script>
    import Replies from '../components/Replies.vue';

    export default {
        name: "thread-view",
        props: ['initialRepliesCount'],
        components: { Replies },
        data() {
            return {
                repliesCount : this.initialRepliesCount
            }
        },
    }
</script>
