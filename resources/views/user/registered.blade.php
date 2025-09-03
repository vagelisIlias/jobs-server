<x-mail::message>
# Introduction

The body of your message.{{$user_name}}

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
