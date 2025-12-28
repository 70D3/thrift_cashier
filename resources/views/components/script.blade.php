<script src="{{ url('assets/vendors/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<script src="/livewire/livewire.js"></script>

<!-- Midtrans -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('pay-midtrans', ({
        snapToken
    }) => {

        // tutup modal bootstrap dulu (anti blank)
        const modal = bootstrap.Modal.getInstance(
            document.getElementById('checkoutNonTunai')
        );
        modal?.hide();

        snap.pay(snapToken, {
            onSuccess: function(result) {
                console.log('SUCCESS', result);

                // redirect ke route cashier.success
                window.location.href = `/`;
            },
            onPending: function(result) {
                console.log('PENDING', result);
            },
            onError: function(result) {
                console.log('ERROR', result);
            },
            onClose: function() {
                alert('Popup ditutup');
            }

        });

    });
});
</script>