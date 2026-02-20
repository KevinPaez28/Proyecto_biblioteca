<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar correo</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 600px; margin: 0 auto; background-color: #f7fafc; padding: 20px 0;">
    
    <div style="background: white; border-radius: 8px; box-shadow: 0 10px 25px -5px rgba(0, 0,0, 0.1), 0 4px 6px -2px rgba(0, 0,0, 0.05); margin: 0 20px; padding: 0;">
        
        <!-- Header -->
        <div style="background-color: #10b981; color: white; padding: 32px 40px; border-radius: 8px 8px 0 0; text-align: center;">
            <h1 style="font-size: 24px; font-weight: 500; margin: 0; line-height: 1.3;">
                Confirma tu correo
            </h1>
        </div>
        
        <!-- Content -->
        <div style="padding: 40px;">
            <h2 style="font-size: 20px; font-weight: 500; color: #2d3748; margin: 0 0 8px;">
                Hola {{ $first_name }} {{ $last_name }},
            </h2>

            <p style="font-size: 16px; color: #4a5568; margin: 0 0 32px 0;">
                Estás a un paso de activar tu cuenta en el sistema de asistencias.
                Por favor confirma tu correo electrónico para continuar.
            </p>
            
            <!-- Bloque principal -->
            <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 2px solid #10b981; border-radius: 12px; padding: 32px; margin: 0 0 40px 0; box-shadow: 0 4px 12px rgba(16,185,129,0.15); text-align: center;">
                
                <h3 style="font-size: 18px; font-weight: 600; color: #065f46; margin: 0 0 16px 0;">
                    Confirma tu correo electrónico
                </h3>

                <p style="font-size: 15px; color: #047857; margin: 0 0 24px 0;">
                    Haz clic en el botón de abajo para verificar tu correo y activar tu cuenta.
                </p>

                <a href="{{ $verification_url }}"
                   style="display: inline-block; background-color: #10b981; color: white; padding: 14px 36px; font-size: 16px; font-weight: 500; text-decoration: none; border-radius: 6px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                    Confirmar correo
                </a>
            </div>
            
            <!-- Nota -->
            <div style="background-color: #fffbeb; border: 1px solid #fcd34d; border-radius: 8px; padding: 20px; margin: 0 0 32px 0;">
                <p style="font-size: 14px; color: #92400e; margin: 0;">
                    ⚠️ Si no solicitaste esta cuenta, puedes ignorar este correo.
                </p>
            </div>

            <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 32px 0;">

            <p style="font-size: 14px; color: #a0aec0; line-height: 1.5; margin: 0;">
                Si tienes dudas, contacta al administrador del sistema.
            </p>
        </div>
        
        <!-- Footer -->
        <div style="background-color: #f7fafc; padding: 32px 40px; border-top: 1px solid #e2e8f0; border-radius: 0 0 8px 8px; text-align: center;">
            <p style="font-size: 14px; color: #a0aec0; margin: 0 0 8px;">
                Sistema de Asistencias
            </p>
            <p style="font-size: 12px; color: #a0aec0; margin: 0;">
                © {{ date('Y') }} Todos los derechos reservados.
            </p>
        </div>
    </div>
</body>
</html>
