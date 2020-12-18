@if ($message = Session::get('pass'))
<div class="alert alert-success alert-block" id="pass-alert">
        <strong>Success! </strong>{{ $message }}
</div>
@endif

<script>
    $(document).ready(function () {
        $("#pass-alert")
            .fadeTo(8000, 7000)
            .slideUp(7000, function () {
                $("#pass-alert").slideUp(7000);
            });
    });
</script>