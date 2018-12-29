export default {
    data() {
        return {
            items: []
        }
    },
    methods: {
        add(item) {
            // console.log('item', item);
            this.items.push(item);
            this.$emit('add');
        },
        remove(index) {
            // console.log('remove', index);
            this.items.splice(index, 1);
            this.$emit('remove');
            flash('Reply was deleted!');
        }
    }
}
