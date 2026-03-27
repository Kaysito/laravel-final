<div style="font-family: Arial, sans-serif; background-color: #0a0a0f; color: #f0f0f5; padding: 40px 20px; text-align: center;">
    <div style="max-width: 500px; margin: 0 auto; background-color: #111118; border: 1px solid #22222c; border-radius: 12px; padding: 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.5);">
        
        <h2 style="color: #f0f0f5; margin-bottom: 5px;">¡Bienvenido al Sistema!</h2>
        <p style="color: #a0a0b0; font-size: 14px; line-height: 1.5;">Hola <strong>{{ $usuario->strNombreUsuario }}</strong>, tu cuenta ha sido creada exitosamente. Para activarla y poder iniciar sesión, haz clic en el siguiente botón:</p>
        
        <div style="margin: 35px 0;">
            <a href="{{ route('verificar.correo', ['id' => $usuario->id, 'codigo' => $codigo]) }}" 
               style="background-color: #e63757; color: #ffffff; padding: 14px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 15px; display: inline-block; letter-spacing: 1px; box-shadow: 0 4px 15px rgba(230,55,87,0.4);">
               Activar mi Cuenta
            </a>
        </div>

        <div style="text-align: left; background-color: #0a0a0f; padding: 15px; border-radius: 8px; border-left: 4px solid #3b82f6;">
            <p style="margin: 0 0 5px 0; font-size: 13px; color: #a0a0b0;">Tus credenciales de acceso seguras:</p>
            <p style="margin: 0; font-size: 14px; color: #f0f0f5;"><strong>Email:</strong> {{ $usuario->strCorreo }}</p>
            <p style="margin: 5px 0 0 0; font-size: 14px; color: #f0f0f5;"><strong>Contraseña:</strong> {{ $pwdTemporal }}</p>
        </div>

        <p style="color: #60607a; font-size: 11px; margin-top: 30px;">Si no solicitaste esta cuenta, por favor ignora este correo. El enlace es de un solo uso.</p>
    </div>
</div>