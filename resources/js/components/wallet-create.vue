<template>
    <div class="card mt-2">
        <div class="card-header">Create Wallet</div>
        <div class="card-body">
            <form>
                <input class="input-group-sm" type="text" v-model="wallet.name">
                <button :disabled="loading" type="button" v-on:click="create" class="btn btn-sm btn-primary">Create</button>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    data: function() {
        return {
            wallet: {
                name: '',
            },
            loading: false
        };
    },
    mounted() {
    },
    methods: {
        create: function (e) {

            this.loading = true;

            axios
                .post('/wallet/create', this.wallet)
                .then((res) => {
                    this.$emit('add-wallet', res.data);
                    this.loading = false;
                    this.wallet.name = '';
                })
        }
    }
}
</script>
