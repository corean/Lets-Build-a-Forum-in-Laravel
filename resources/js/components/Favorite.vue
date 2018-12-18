<template>
    <button type="submit" :class="classes" @click="toggle()">
        <span class="glyphicon glyphicon-heart"></span>
        <span v-text="count"></span>
    </button>
</template>

<script>
    export default {
        name: "Favorite",
        props: ['reply'],
        data() {
            return {
                count: this.reply.favoritesCount,
                active: this.reply.isFavorited,
                endpoint: '/replies/' + this.reply.id + '/favorites',
            }
        },
        computed: {
            classes() {
                return [
                    'btn',
                    this.active ? 'btn-primary' : 'btn-default'
                ];
            }
        },
        methods: {
            toggle() {
                this.active ? this.destroy() : this.create();
            },
            destroy() {
                axios.delete(this.endpoint);
                this.active = false;
                this.count--;
            },
            create() {
                axios.post(this.endpoint);
                this.active = true;
                this.count++;
            }
        },

    }
</script>
