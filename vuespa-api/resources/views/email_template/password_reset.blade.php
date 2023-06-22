<!DOCTYPE html>
<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">

<head>
    <title></title>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]-->
    <!--[if !mso]><!-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--<![endif]-->
    <style>
    html,
    body {
        padding: 0;
        margin: 0;
        font-family: Inter, Helvetica, "sans-serif";
    }
    </style>
</head>

<body style="background-color: #D5D9E2;">
    <div id="#kt_app_body_content"
        style="font-family:Arial,Helvetica,sans-serif; line-height: 1.5; min-height: 100%; font-weight: normal; font-size: 15px; color: #2F3044; margin:0; padding:40px 15px; width:100%;">
        <div
            style="background-color:#ffffff; padding: 45px 0 34px 0; border-radius: 24px; margin:40px auto; max-width: 600px;">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" height="auto"
                style="border-collapse:collapse">
                <tbody>
                    <tr>
                        <td align="center" valign="center" style="text-align:center; padding-bottom: 10px">
                            <!--begin:Email content-->
                            <div style="text-align:center; margin:0 15px 34px 15px">
                                <!--begin:Logo-->
                                <div style="margin-bottom: 10px">

                                </div>
                                <!--end:Logo-->
                                <!--begin:Text-->
                                <div
                                    style="font-size: 14px; font-weight: 500; margin-bottom: 27px; font-family:Arial,Helvetica,sans-serif;">
                                    <p style="margin-bottom:9px; color:#181C32; font-size: 22px; font-weight:700">Hey
                                        {{$name}}, Reset your password</p>
                                    <p style="color:#7E8299;margin-top: 2px;margin-bottom: 2px;">click below button to
                                        reset your password.</p>
                                    <p style="color:#7E8299;margin-top: 2px;margin-bottom: 2px;">If you find trouble in
                                        clicking button then use below link to reset password.</p>
                                </div>
                                <!--end:Text-->
                                <!--begin:Action-->
                                <a href='{{$reset_link}}' rel="noopener" target="_blank"
                                    style="background-color:#181C32; border-radius:6px;display:inline-block; padding:11px 19px; color: #FFFFFF; font-size: 14px; font-weight:500;text-decoration: none;">Reset
                                    Password</a>
                                <br /><a href="{{$reset_link}}" rel="noopener" target="_blank"
                                    style="margin-top: 32px;display: block">{{$reset_link}}</a>
                                <!--begin:Action-->
                            </div>
                            <!--end:Email content-->
                        </td>
                    </tr>

                    <tr>
                        <td align="center" valign="center"
                            style="font-size: 13px; padding:0 15px; text-align:center; font-weight: 500; color: #A1A5B7;font-family:Arial,Helvetica,sans-serif">
                            <p>&copy; Copyright VueSPA</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>