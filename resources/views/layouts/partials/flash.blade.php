@php
    $hasToasts = session('success') || session('error') || $errors->any();
@endphp

@if($hasToasts)
    <div class="toast-container" role="status" aria-live="polite">
        @if(session('success'))
            <div class="toast success" data-autoclose="1">
                <i class="bi bi-check-circle" style="color:var(--success)"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="toast error" data-autoclose="1">
                <i class="bi bi-exclamation-circle" style="color:var(--critical)"></i> {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="toast error" data-autoclose="1">
                <strong style="display:block;margin-bottom:4px">
                    <i class="bi bi-exclamation-triangle" style="color:var(--critical)"></i>
                    Periksa kembali isian berikut:
                </strong>
                <ul class="mb-0 mt-1" style="padding-left:18px;color:var(--text-muted)">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endif
