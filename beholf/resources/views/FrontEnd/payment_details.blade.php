@if($booking->pembayaran && $booking->pembayaran->status == 'pending')
    <div class="modal fade" id="paymentModal{{ $booking->id }}" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Payment Proof</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Payment Proof Image -->
                    @if($booking->pembayaran && $booking->pembayaran->foto_pembayaran)
                      <img src="{{ asset('uploads/payments/' . $booking->pembayaran->foto_pembayaran) }}" alt="Payment Proof" class="img-fluid mb-3">
                    @else
                        <small class="text-muted">No image available</small> <!-- Placeholder Text if no image -->
                        <!-- Or you can add a default image -->
                        <img src="{{ asset('uploads/default_payment_image.jpg') }}" alt="No Payment Image" class="img-fluid mb-3" style="width: 100%; height: auto; object-fit: cover;">
                    @endif
    
                    <!-- Approve or Reject Buttons -->
                    <a href="{{ route('payment.approve', $booking->pembayaran->id) }}" class="btn btn-success">Lunas</a>
                    <a href="{{ route('payment.reject', $booking->pembayaran->id) }}" class="btn btn-danger">Gagal</a>
                </div>
            </div>
        </div>
    </div>
@endif
