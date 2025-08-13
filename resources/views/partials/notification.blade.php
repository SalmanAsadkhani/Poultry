<div id="notification-prompt-modal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <img src="{{ asset('assets/images/bell-icon.png') }}" alt="Notification Bell" width="80" class="mb-3">
                <h5 class="fw-bold">یادآورها را فعال کنید</h5>
                <p class="text-muted">
                    آیا مایلید یادآورهای روزانه را دریافت کنید؟
                </p>
                <div class="d-flex justify-content-center mt-4">
                    <button id="decline-notifications-btn" type="button" class="btn btn-secondary mx-2" data-bs-dismiss="modal">بعداً، شاید</button>
                    <button id="accept-notifications-btn" type="button" class="btn btn-primary mx-2">بله، فعال کن</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // --- بخش ۱: توابع کمکی ---

        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);
            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }

        function updateSubscriptionOnServer(subscription) {
            fetch('{{ route("push_subscriptions.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(subscription)
            }).then(response => {
                if (!response.ok) throw new Error('Network response was not ok.');
                return response.json();
            }).then(data => {
                console.log('Subscription updated on server:', data);
            }).catch(error => {
                console.error('Error updating subscription on server:', error);
            });
        }

        function subscribeUser() {
            navigator.serviceWorker.ready.then(registration => {
                const applicationServerKey = urlBase64ToUint8Array(window.vapidPublicKey);

                registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: applicationServerKey
                }).then(subscription => {
                    console.log('User is subscribed.');
                    updateSubscriptionOnServer(subscription);
                }).catch(err => {
                    console.error('Failed to subscribe the user: ', err);
                });
            });
        }


        if (!('Notification' in window) || !('serviceWorker' in navigator)) {
            return;
        }

        const modalEl = document.getElementById('notification-prompt-modal');
        const acceptBtn = document.getElementById('accept-notifications-btn');
        const declineBtn = document.getElementById('decline-notifications-btn');

        if (!modalEl) return;

        const notificationModal = new bootstrap.Modal(modalEl);

        if (Notification.permission === 'default' && !localStorage.getItem('notificationPromptShown')) {
            setTimeout(() => {
                notificationModal.show();
                localStorage.setItem('notificationPromptShown', 'true');
            }, 8000);
        }

        acceptBtn.addEventListener('click', function() {
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    toastr.success('یادآورها با موفقیت فعال شدند.');
                    subscribeUser();
                } else {
                    toastr.error('شما اجازه ارسال یادآورها را ندادید.');
                }
                notificationModal.hide();
            });
        });

        declineBtn.addEventListener('click', function() {
            notificationModal.hide();
        });
    });
</script>
