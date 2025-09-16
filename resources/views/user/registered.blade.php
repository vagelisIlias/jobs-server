<x-mail::message>
# Welcome, {{$user_name}} 🎉

Thank you for registering with us!
We’re excited to have you on board. Please confirm your email address to get started.

<x-mail::button :url="$url">
Confirm My Email
</x-mail::button>

If you did not create an account, no action is required.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

