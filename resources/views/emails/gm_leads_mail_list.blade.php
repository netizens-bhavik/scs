<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>New Leads</title>
</head>

{{-- <body>

    Hi {{ $details['user_name'] }},

    Just wanted to let you know that new leads are now available in your account. Go ahead and login to your account and
    check for yourself.


    <table border="2px">
        <thead>
            <tr>
                <th>Name</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($details['mailList'] as $mail)
                <tr>
                    <td>{{ $mail['name'] }}</td>
                    <td>{{ $mail['country'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>


    Feel free to reach out if you have any questions.

    Thanks,

</body> --}}

<body style="margin:0;padding:0" dir="ltr" bgcolor="#ffffff">
    <table border="0" cellspacing="0" cellpadding="0" align="center" id="m_-7626415423304311386email_table"
        style="border-collapse:collapse">
        <tbody>
            <tr>
                <td id="m_-7626415423304311386email_content"
                    style="font-family:Helvetica Neue,Helvetica,Lucida Grande,tahoma,verdana,arial,sans-serif;background:#ffffff">
                    <table border="0" width="100%" cellspacing="0" cellpadding="0"
                        style="border-collapse:collapse">
                        <tbody>
                            <tr>
                                <td height="20" style="line-height:20px" colspan="3">&nbsp;</td>
                            </tr>
                            <tr>
                                <td height="1" colspan="3" style="line-height:1px"></td>
                            </tr>
                            <tr>
                                <td>
                                    <table border="0" width="100%" cellspacing="0" cellpadding="0"
                                        style="border-collapse:collapse;text-align:center;width:100%">
                                        <tbody>
                                            <tr>
                                                <td width="15px" style="width:15px"></td>
                                                <td style="line-height:0px;max-width:600px;padding:0 0 15px 0">
                                                    <table border="0" width="100%" cellspacing="0" cellpadding="0"
                                                        style="border-collapse:collapse">
                                                        <tbody>
                                                            <tr>
                                                                <td style="width:100%;text-align:left;height:33px"><img
                                                                        height="33"
                                                                        src="{{ $details['app_url'] . '/' . 'assets/img/logo.png' }}"
                                                                        style="border:0" class="CToWUd" data-bit="iit">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td width="15px" style="width:15px"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table border="0" width="430" cellspacing="0" cellpadding="0"
                                        style="border-collapse:collapse;margin:0 auto 0 auto">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <table border="0" width="430px" cellspacing="0" cellpadding="0"
                                                        style="border-collapse:collapse;margin:0 auto 0 auto;width:430px">
                                                        <tbody>
                                                            <tr>
                                                                <td width="15" style="display:block;width:15px">
                                                                    &nbsp;&nbsp;&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <table border="0" width="100%" cellspacing="0"
                                                                        cellpadding="0"
                                                                        style="border-collapse:collapse">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <table border="0"
                                                                                        cellspacing="0" cellpadding="0"
                                                                                        style="border-collapse:collapse">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td width="20"
                                                                                                    style="display:block;width:20px">
                                                                                                    &nbsp;&nbsp;&nbsp;
                                                                                                </td>
                                                                                                <td>
                                                                                                    <table
                                                                                                        border="0"
                                                                                                        cellspacing="0"
                                                                                                        cellpadding="0"
                                                                                                        style="border-collapse:collapse">
                                                                                                        <tbody>
                                                                                                            <tr>
                                                                                                                <td>
                                                                                                                    <p
                                                                                                                        style="margin:10px 0 10px 0;color:#565a5c;font-size:18px">
                                                                                                                        Hi
                                                                                                                        <span
                                                                                                                            style="font-weight:bold">
                                                                                                                            {{ $details['user_name'] }}
                                                                                                                        </span>
                                                                                                                    </p>
                                                                                                                    <p
                                                                                                                        style="margin:10px 0 10px 0;color:#565a5c;font-size:18px">
                                                                                                                        Just
                                                                                                                        wanted
                                                                                                                        to
                                                                                                                        let
                                                                                                                        you
                                                                                                                        know
                                                                                                                        that
                                                                                                                        new
                                                                                                                        leads
                                                                                                                        are
                                                                                                                        now
                                                                                                                        available
                                                                                                                        in
                                                                                                                        your
                                                                                                                        account.
                                                                                                                        <br />
                                                                                                                        Go
                                                                                                                        ahead
                                                                                                                        and
                                                                                                                        login
                                                                                                                        to
                                                                                                                        your
                                                                                                                        account
                                                                                                                        and
                                                                                                                        check
                                                                                                                        for
                                                                                                                        yourself.
                                                                                                                    </p>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td height="20"
                                                                                                                    style="line-height:20px">
                                                                                                                    &nbsp;
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td>
                                                                                                                    <table
                                                                                                                        border="2px"
                                                                                                                        style="width: 100%;border-collapse: collapse;">
                                                                                                                        <thead>
                                                                                                                            <tr>
                                                                                                                                <th>Name
                                                                                                                                </th>
                                                                                                                                <th>Address
                                                                                                                                </th>
                                                                                                                            </tr>
                                                                                                                        </thead>
                                                                                                                        <tbody>
                                                                                                                            @foreach ($details['mailList'] as $mail)
                                                                                                                                <tr>
                                                                                                                                    <td>{{ $mail['name'] }}
                                                                                                                                    </td>
                                                                                                                                    <td>{{ $mail['country'] }}
                                                                                                                                    </td>
                                                                                                                                </tr>
                                                                                                                            @endforeach
                                                                                                                        </tbody>
                                                                                                                    </table>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                    </a>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td height="20"
                                                                                                    style="line-height:20px">
                                                                                                    &nbsp;
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td height="10" style="line-height:10px" colspan="1">&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td>

                </td>
            </tr>
            <tr>
                <td height="20" style="line-height:20px" colspan="3">&nbsp;</td>
            </tr>
        </tbody>
    </table>
    </tr>
    </tbody>
    </table>
</body>

</html>
