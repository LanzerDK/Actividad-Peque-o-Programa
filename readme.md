**Problematica**

La Empresa Bet-el Creativa a tenido problemas en la gestion del inventario, estos problemas han traido Pérdidas por Stock Agotado, Exceso de Inventario, Costos de almacenamiento elevados, Riesgo de obsolescencia o caducidad, Falta de Visibilidad en Tiempo Real y Tambien a tenido fallas en su sistema el cual a dejado Vulnerabilidades de Seguridad como Manipulación de precios o stock, Acceso no autorizado a datos sensibles e Inyección de datos maliciosos



 **Como el Codigo que hice Soluciona Esta Problematica**

*Sanitización y Validación de Datos:*
Inyección SQL: Validación de tipos numéricos.

Cross-Site Scripting: htmlspecialchars() en todos los datos de entrada.

Datos corruptos: Validación de rangos y formatos

Valores extremos: Límites en precios y cantidades

*Validación en Tiempo Real*

*Sistema de Alertas Automatizado*
