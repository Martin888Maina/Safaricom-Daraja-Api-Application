@if(session('mpesa_response'))
    @php
        $resp    = session('mpesa_response');
        $code    = $resp['ResponseCode'] ?? $resp['ResultCode'] ?? null;
        $success = $code === '0' || $code === 0;
    @endphp
    <div class="response-card {{ $success ? 'success' : 'error' }}">
        <h3>{{ $success ? 'Success — Daraja Response' : 'Response Received' }}</h3>
        <pre>{{ json_encode($resp, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    </div>
@endif
