<template>
    <div>
        <wallet-list ref="list" :wallets="wallets"></wallet-list>

        <wallet-create @add-wallet="addWallet"></wallet-create>
    </div>
</template>

<script>
export default {
    props: ['wallets'],
    methods: {
        addWallet: function(wallet) {
            this.$refs.list.addWallet(wallet);
        }
    }
}
</script>
