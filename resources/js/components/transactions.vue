<template>
    <div class="card mt-2">
        <div class="card-header">Transactions</div>
        <div class="card-body">
            <table class="table text-left">
                <tr class="">
                    <th>date</th>
                    <th>from</th>
                    <th>to</th>
                    <th>type</th>
                    <th>revert</th>
                    <th class="text-right">amount</th>
                </tr>
                <tr v-for="transaction in transactions">
                    <td>{{transaction.created_at}}</td>
                    <td>{{transaction.from.user.email}} : {{transaction.from.name}}</td>
                    <td>{{transaction.to.user.email}} : {{transaction.to.name}}</td>
                    <td>{{type(transaction)}}</td>
                    <td>
                        <button class="btn btn-sm btn-dark" v-on:click="revert(transaction)">Delete</button>
                    </td>
                    <td class="text-right">{{amount(transaction)}}</td>
                </tr>
            </table>

            <div class="col-md-12 text-right">
                Total in: {{totalIn()}} <br>
                Total out: {{totalOut()}} <br>
                Current Amount: {{ myWallet.amount }}
            </div>

            <form>
                <input type="text" placeholder="email" v-model="newTransaction.email">
                <input type="text" placeholder="wallet" v-model="newTransaction.wallet">
                <input type="text" placeholder="amount" v-model="newTransaction.amount">
                <button type="submit" class="btn btn-sm btn-success" v-on:click="send">Send</button>
            </form>

            <div v-show="errors.length > 0">
                <ul>
                    <li v-for="error in errors">{{ error }}</li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['wallet'],
    data: function () {
        return {
            myWallet: {},
            errors: [],
            newTransaction: {
                email: '',
                wallet: '',
                amount: null
            },
            transactions: []
        };
    },
    mounted() {
        this.myWallet = this.wallet;
        this.fetch();
    },
    methods: {
        fetch: function () {
            axios.get('/wallet/' + this.myWallet.id + '/transactionsList')
                .then((res) => {
                    this.transactions = res.data.data || res.data;
                })
        },
        isOutgoing(transaction) {
            return transaction.from_wallet === this.myWallet.id;
        },
        type: function (transaction) {
            return this.isOutgoing(transaction) ? 'Outgoing' : 'Incoming';
        },
        amount: function (transaction) {
            let amount = this.isOutgoing(transaction) ? -transaction.amount : +transaction.amount;

            return amount.toFixed(2);
        },
        send(e) {
            e.preventDefault();

            if (parseFloat(this.newTransaction.amount) > parseFloat(this.myWallet.amount)) {
                alert('not enough money');
                return;
            }

            axios.post('/wallet/' + this.wallet.id + '/transaction/create', this.newTransaction)
                .then((res) => {
                    this.myWallet.amount = this.myWallet.amount - this.newTransaction.amount;
                    this.transactions.unshift(res.data);
                    alert('Success');
                }, this.error);
        },
        revert: function (transaction) {
            axios.delete('/wallet/' + this.myWallet.id + '/transaction/delete/' + transaction.id).then(() => {
                this.transactions = this.transactions.filter(t => t.id !== transaction.id);
            }, this.error);
        },
        error: function (res) {
            this.errors = Object.values(res.response.data.errors).reduce((all, values) => {
                values.map(val => all.push(val));
                return all;
            }, []);
            setTimeout(() => {
                this.errors = [];
            }, 5000);
        },
        totalIn: function () {
            return this.transactions.reduce((sum, transaction) => {
                if (!this.isOutgoing(transaction)) {
                    sum += parseFloat(transaction.amount);
                }
                return sum;
            }, 0).toFixed(2);
        },
        totalOut: function () {
            return (-this.transactions.reduce((sum, transaction) => {
                if (this.isOutgoing(transaction)) {
                    sum += parseFloat(transaction.amount);
                }
                return sum;
            }, 0)).toFixed(2);
        },
    }
}
</script>
