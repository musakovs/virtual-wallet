<template>
    <div class="card">
        <div class="card-header">Wallet List</div>

        <div class="card-body">
            <table width="100%">
                <tr>
                    <th>Name</th>
                    <th>Amount</th>
                    <th colspan="4">Actions</th>
                </tr>

                <tr v-for="wallet in myWallets">
                    <td><input style="border: 0;outline: none;" v-model="wallet.name"></td>
                    <td>{{wallet.amount}}</td>
                    <td>
                        <button class="btn btn-sm btn-danger" v-on:click="deleteWallet(wallet.id)">Delete</button>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-success" v-on:click="renameWallet(wallet)">Rename</button>
                    </td>
                    <td>
                        <a v-bind:href="transactionsLink(wallet)" class="btn btn-sm">Transactions</a>
                    </td>
                </tr>

            </table>
        </div>
    </div>
</template>

<script>
export default {
    props: ['wallets'],
    data: function() {
        return {
            myWallets: []
        }
    },
    mounted() {
        this.myWallets = this.wallets;
    },
    methods: {
        addWallet: function(wallet) {
            this.myWallets.unshift(wallet);
        },
        deleteWallet: function(id) {
            axios.delete('/wallet/delete/' + id).then((res) => {
                this.myWallets = this.myWallets.filter(wallet => wallet.id !== id)
            })
        },
        renameWallet: function(wallet) {
            axios.post('/wallet/update/' + wallet.id, wallet).then(() => {
                alert('Wallet Renamed')
            })
        },
        transactionsLink: function(wallet) {
            return '/wallet/' + wallet.id + '/transactions';
        }
    }
}
</script>
