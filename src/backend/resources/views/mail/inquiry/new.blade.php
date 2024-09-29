@component('mail::message')
# A new Inquiry has been submitted.

```
Fullname: {{ $fullname }}
Email: {{ $email }}
Content: {{ $content }}
```

Thanks,<br>
{{ config('app.name') }}
@endcomponent
