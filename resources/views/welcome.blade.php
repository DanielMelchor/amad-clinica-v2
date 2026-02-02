<!-- <!DOCTYPE html>
<html lang="es"> -->
<!-- <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Software Médico | Demo Gratis</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .hero {
            background: linear-gradient(to right, #00a8ff, #0062ff);
            color: white;
            padding: 60px 0;
            text-align: center;
        }
        .cta-button {
            background-color: #ffffff;
            color: #0062ff;
            border: none;
            padding: 15px 30px;
            font-weight: bold;
            font-size: 18px;
            border-radius: 5px;
        }
        .features i {
            font-size: 40px;
            margin-bottom: 10px;
            color: #0062ff;
        }
        .form-section {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        footer {
            text-align: center;
            padding: 20px 0;
            font-size: 12px;
            color: #888;
        }
    </style>
</head> -->
<!-- <body> -->

    <!-- Hero -->
    <!-- <section class="hero">
        <div class="container">
            <h1 class="display-4">El Software Ideal para Médicos</h1>
            <p class="lead">Agenda, historial clínico, facturación y más... todo en un solo lugar.</p>
            <a href="#formulario" class="btn cta-button mt-3">Solicita tu Demo Gratis</a>
        </div>
    </section> -->

    <!-- Características -->
    <!-- <section class="features py-5">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-4">
                    <i class="bi bi-calendar2-check"></i>
                    <h5 class="mt-2">Agenda Inteligente</h5>
                    <p>Organiza tus citas y turnos de forma automática.</p>
                </div>
                <div class="col-md-4">
                    <i class="bi bi-file-earmark-text"></i>
                    <h5 class="mt-2">Historial Clínico</h5>
                    <p>Accede a fichas y evolución médica desde cualquier lugar.</p>
                </div>
                <div class="col-md-4">
                    <i class="bi bi-cash-coin"></i>
                    <h5 class="mt-2">Facturación Electrónica</h5>
                    <p>Emite boletas y facturas en segundos.</p>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Formulario -->
    <!-- <section class="py-5" id="formulario">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 form-section">
                    <h3 class="text-center mb-4">Solicita tu Demo</h3>
                    <form action="#" method="post">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre completo</label>
                            <input type="text" class="form-control" id="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="especialidad" class="form-label">Especialidad médica</label>
                            <input type="text" class="form-control" id="especialidad">
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="correo" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Solicitar demo</button>
                    </form>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Footer -->
    <!-- <footer class="bg-light">
        <div class="container">
            © 2025 Software Médico Pro - Todos los derechos reservados
        </div>
    </footer> -->

    <!-- Bootstrap icons (opcional) -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.js"></script>
</body>
</html> -->


<!-- el codigo que sigue es una version que me gusto -->

<!-- <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centro Médico Salud & Bienestar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-slate-50 font-sans text-gray-800">

    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="text-2xl font-bold text-blue-600 flex items-center">
                <i class="fas fa-hand-holding-medical mr-2"></i> SaludVital
            </div>
            <div class="hidden md:flex space-x-8 font-medium">
                <a href="#inicio" class="hover:text-blue-600 transition">Inicio</a>
                <a href="#servicios" class="hover:text-blue-600 transition">Servicios</a>
                <a href="#nosotros" class="hover:text-blue-600 transition">Nosotros</a>
                <a href="#contacto" class="bg-blue-600 text-white px-5 py-2 rounded-full hover:bg-blue-700 transition">Agendar Cita</a>
            </div>
        </div>
    </nav>

    <section id="inicio" class="relative bg-blue-600 py-20 lg:py-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center relative z-10">
            <div class="md:w-1/2 text-white mb-10 md:mb-0">
                <h1 class="text-4xl md:text-6xl font-bold leading-tight mb-6">Su salud es nuestra prioridad número uno</h1>
                <p class="text-lg mb-8 text-blue-100">Ofrecemos atención médica integral con tecnología de vanguardia y un equipo humano altamente calificado.</p>
                <div class="flex space-x-4">
                    <a href="#contacto" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-bold hover:bg-gray-100 transition shadow-lg">Reservar Cita</a>
                    <a href="#servicios" class="border-2 border-white text-white px-8 py-3 rounded-lg font-bold hover:bg-white hover:text-blue-600 transition">Ver Servicios</a>
                </div>
            </div>
            <div class="md:w-1/2 flex justify-center">
                <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&w=800&q=80" alt="Doctor" class="rounded-2xl shadow-2xl border-8 border-white/20 w-4/5">
            </div>
        </div>
        <div class="absolute top-0 right-0 -mt-20 -mr-20 bg-blue-500 rounded-full h-80 w-80 opacity-20"></div>
    </section>

    <section id="servicios" class="py-20 max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Especialidades Médicas</h2>
            <div class="h-1 w-20 bg-blue-600 mx-auto"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-xl shadow-sm hover:shadow-md transition border-t-4 border-blue-500">
                <div class="text-blue-500 text-4xl mb-4"><i class="fas fa-heartbeat"></i></div>
                <h3 class="text-xl font-bold mb-3">Cardiología</h3>
                <p class="text-gray-600">Cuidado especializado para el corazón con diagnósticos precisos y tratamientos avanzados.</p>
            </div>
            <div class="bg-white p-8 rounded-xl shadow-sm hover:shadow-md transition border-t-4 border-blue-500">
                <div class="text-blue-500 text-4xl mb-4"><i class="fas fa-baby"></i></div>
                <h3 class="text-xl font-bold mb-3">Pediatría</h3>
                <p class="text-gray-600">Atención integral y dedicada para el crecimiento saludable de los más pequeños.</p>
            </div>
            <div class="bg-white p-8 rounded-xl shadow-sm hover:shadow-md transition border-t-4 border-blue-500">
                <div class="text-blue-500 text-4xl mb-4"><i class="fas fa-microscope"></i></div>
                <h3 class="text-xl font-bold mb-3">Laboratorio</h3>
                <p class="text-gray-600">Resultados rápidos y confiables para todos tus análisis clínicos esenciales.</p>
            </div>
        </div>
    </section>

    <section id="contacto" class="bg-slate-100 py-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden flex flex-col md:flex-row">
                <div class="md:w-1/3 bg-blue-700 p-12 text-white">
                    <h3 class="text-2xl font-bold mb-6">Información de Contacto</h3>
                    <p class="mb-8 opacity-90">Estamos aquí para ayudarte. Contáctanos por cualquiera de estos medios.</p>
                    <div class="space-y-6">
                        <div class="flex items-center">
                            <i class="fas fa-phone-alt mr-4 text-blue-300"></i>
                            <span>+52 (55) 1234 5678</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope mr-4 text-blue-300"></i>
                            <span>contacto@saludvital.com</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-4 text-blue-300"></i>
                            <span>Av. Médica 123, Ciudad Salud</span>
                        </div>
                    </div>
                </div>
                <div class="md:w-2/3 p-12">
                    <h3 class="text-2xl font-bold mb-8 text-gray-800">Agendar una Consulta</h3>
                    <form action="#" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <input type="text" placeholder="Nombre completo" class="border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <input type="email" placeholder="Correo electrónico" class="border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <select class="border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option>Seleccione Especialidad</option>
                            <option>Medicina General</option>
                            <option>Cardiología</option>
                            <option>Pediatría</option>
                        </select>
                        <input type="date" class="border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <textarea placeholder="Motivo de la consulta" class="md:col-span-2 border border-gray-300 p-3 rounded-lg h-32 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        <button class="md:col-span-2 bg-blue-600 text-white font-bold py-4 rounded-lg hover:bg-blue-700 transition">Enviar Solicitud</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-gray-400 py-10 text-center">
        <p>© 2026 Centro Médico SaludVital. Todos los derechos reservados.</p>
    </footer>

</body>
</html> -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Avanzada Salud & Vida</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        /* Estilos personalizados para el carrusel y superposición */
        .swiper-container {
            width: 100%;
            height: 600px; /* Ajusta la altura del carrusel */
        }
        .swiper-slide {
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
            position: relative;
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, rgba(28, 77, 160, 0.8), rgba(0, 114, 206, 0.5)); /* Superposición azul para texto */
        }
        .swiper-pagination-bullet-active {
            background-color: white !important;
        }
        .swiper-button-prev, .swiper-button-next {
            color: white !important;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans text-gray-800">

    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="text-2xl font-bold text-blue-700 flex items-center">
                <i class="fas fa-stethoscope mr-2"></i> SaludIntegral
            </div>
            <div class="hidden md:flex space-x-8 font-medium">
                <a href="#inicio" class="hover:text-blue-700 transition">Inicio</a>
                <a href="#especialidades" class="hover:text-blue-700 transition">Especialidades</a>
                <a href="#equipo" class="hover:text-blue-700 transition">Nuestro Equipo</a>
                <a href="#contacto" class="bg-blue-700 text-white px-5 py-2 rounded-full hover:bg-blue-800 transition">Pedir Cita</a>
            </div>
        </div>
    </nav>

    <section id="inicio" class="relative">
        <div class="swiper swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide" style="background-image: url('https://images.unsplash.com/photo-1538108149393-fbbd81895907?auto=format&fit=crop&w=1400&q=80');">
                    <div class="overlay"></div>
                    <div class="relative z-10 text-center max-w-3xl mx-auto px-4">
                        <h1 class="text-5xl md:text-6xl font-extrabold leading-tight mb-6 animate-fade-in">Bienvenido a SaludIntegral</h1>
                        <p class="text-xl md:text-2xl mb-8 font-light animate-fade-in animation-delay-300">Cuidamos de ti y de tu familia con pasión y experiencia.</p>
                        <a href="#contacto" class="bg-blue-600 text-white px-10 py-4 rounded-full font-bold text-lg hover:bg-blue-700 transition-all duration-300 shadow-lg animate-fade-in animation-delay-600">Agenda tu Cita Hoy</a>
                    </div>
                </div>
                <div class="swiper-slide" style="background-image: url('https://images.unsplash.com/photo-1550831107-15531475752c?auto=format&fit=crop&w=1400&q=80');">
                    <div class="overlay"></div>
                    <div class="relative z-10 text-center max-w-3xl mx-auto px-4">
                        <h2 class="text-5xl md:text-6xl font-extrabold leading-tight mb-6">Tecnología de Vanguardia</h2>
                        <p class="text-xl md:text-2xl mb-8 font-light">Diagnósticos precisos y tratamientos innovadores a tu alcance.</p>
                        <a href="#especialidades" class="bg-blue-600 text-white px-10 py-4 rounded-full font-bold text-lg hover:bg-blue-700 transition-all duration-300 shadow-lg">Conoce Nuestros Servicios</a>
                    </div>
                </div>
                <div class="swiper-slide" style="background-image: url('https://images.unsplash.com/photo-1628348737375-1a3b118b76b3?auto=format&fit=crop&w=1400&q=80');">
                    <div class="overlay"></div>
                    <div class="relative z-10 text-center max-w-3xl mx-auto px-4">
                        <h2 class="text-5xl md:text-6xl font-extrabold leading-tight mb-6">Tu Bienestar, Nuestra Misión</h2>
                        <p class="text-xl md:text-2xl mb-8 font-light">Un equipo médico comprometido con tu salud y recuperación.</p>
                        <a href="#equipo" class="bg-blue-600 text-white px-10 py-4 rounded-full font-bold text-lg hover:bg-blue-700 transition-all duration-300 shadow-lg">Nuestro Equipo Profesional</a>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>

    <section id="especialidades" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Nuestras Especialidades</h2>
                <p class="text-lg text-gray-600">Ofrecemos una amplia gama de servicios médicos para todas tus necesidades de salud.</p>
                <div class="h-1 w-24 bg-blue-700 mx-auto mt-6"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-blue-50 p-6 rounded-xl shadow-md hover:shadow-lg transition duration-300 text-center border border-blue-100">
                    <div class="text-blue-700 text-5xl mb-4"><i class="fas fa-user-md"></i></div>
                    <h3 class="text-xl font-bold mb-2">Medicina General</h3>
                    <p class="text-gray-600 text-sm">Prevención y tratamiento de enfermedades comunes.</p>
                </div>
                <div class="bg-blue-50 p-6 rounded-xl shadow-md hover:shadow-lg transition duration-300 text-center border border-blue-100">
                    <div class="text-blue-700 text-5xl mb-4"><i class="fas fa-heartbeat"></i></div>
                    <h3 class="text-xl font-bold mb-2">Cardiología</h3>
                    <p class="text-gray-600 text-sm">Expertos en salud cardiovascular.</p>
                </div>
                <div class="bg-blue-50 p-6 rounded-xl shadow-md hover:shadow-lg transition duration-300 text-center border border-blue-100">
                    <div class="text-blue-700 text-5xl mb-4"><i class="fas fa-child"></i></div>
                    <h3 class="text-xl font-bold mb-2">Pediatría</h3>
                    <p class="text-gray-600 text-sm">Cuidado integral para niños y adolescentes.</p>
                </div>
                <div class="bg-blue-50 p-6 rounded-xl shadow-md hover:shadow-lg transition duration-300 text-center border border-blue-100">
                    <div class="text-blue-700 text-5xl mb-4"><i class="fas fa-tooth"></i></div>
                    <h3 class="text-xl font-bold mb-2">Odontología</h3>
                    <p class="text-gray-600 text-sm">Salud bucal para toda la familia.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- <section id="equipo" class="py-20 bg-slate-100">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Conoce a Nuestro Equipo</h2>
                <p class="text-lg text-gray-600">Profesionales dedicados a tu bienestar con experiencia y calidez humana.</p>
                <div class="h-1 w-24 bg-blue-700 mx-auto mt-6"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden text-center hover:shadow-xl transition duration-300">
                    <img src="https://images.unsplash.com/photo-1559839734-2b716b1772aa?auto=format&fit=crop&w=400&h=400&q=80" alt="Dra. Ana García" class="w-full h-64 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Dra. Ana García</h3>
                        <p class="text-blue-700 font-medium mb-3">Cardióloga Especialista</p>
                        <p class="text-gray-600 text-sm">Con más de 15 años de experiencia, la Dra. García es líder en cardiología preventiva.</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden text-center hover:shadow-xl transition duration-300">
                    <img src="https://images.unsplash.com/photo-1612348316275-c4c01b9794d0?auto=format&fit=crop&w=400&h=400&q=80" alt="Dr. Luis Pérez" class="w-full h-64 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Dr. Luis Pérez</h3>
                        <p class="text-blue-700 font-medium mb-3">Pediatra General</p>
                        <p class="text-gray-600 text-sm">Dedicado al bienestar de los más jóvenes, el Dr. Pérez ofrece un trato amable y experto.</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden text-center hover:shadow-xl transition duration-300">
                    <img src="https://images.unsplash.com/photo-1594824476967-ce62255757d7?auto=format&fit=crop&w=400&h=400&q=80" alt="Dra. Laura Montes" class="w-full h-64 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Dra. Laura Montes</h3>
                        <p class="text-blue-700 font-medium mb-3">Médico General</p>
                        <p class="text-gray-600 text-sm">Enfoque integral en la salud de sus pacientes y prevención de enfermedades.</p>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
    <section id="equipo" class="py-20 max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800">Nuestro Equipo Médico</h2>
            <p class="text-gray-500">Conoce a los profesionales que cuidarán de ti.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <img src="https://images.unsplash.com/photo-1537368910025-700350fe46c7?q=80&w=600&auto=format&fit=crop" class="w-full h-72 object-cover" alt="Doctor">
                <div class="p-6">
                    <h3 class="font-bold text-xl">Dr. Roberto Sánchez</h3>
                    <p class="text-blue-600 font-semibold mb-4">Cirujano General</p>
                    <p class="text-gray-600 text-sm">Experto en procedimientos mínimamente invasivos con 10 años de trayectoria.</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <img src="https://images.unsplash.com/photo-1559839734-2b716b1772aa?q=80&w=600&auto=format&fit=crop" class="w-full h-72 object-cover" alt="Doctora">
                <div class="p-6">
                    <h3 class="font-bold text-xl">Dra. Elena Martínez</h3>
                    <p class="text-blue-600 font-semibold mb-4">Pediatra Especialista</p>
                    <p class="text-gray-600 text-sm">Dedicada a la salud infantil con un enfoque cálido y profesional.</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <img src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?q=80&w=600&auto=format&fit=crop" class="w-full h-72 object-cover" alt="Doctor">
                <div class="p-6">
                    <h3 class="font-bold text-xl">Dr. Javier López</h3>
                    <p class="text-blue-600 font-semibold mb-4">Cardiólogo</p>
                    <p class="text-gray-600 text-sm">Especialista en prevención cardiovascular y salud del corazón.</p>
                </div>
            </div>
        </div>
    </section>


    <section id="contacto" class="bg-blue-800 py-20 text-white">
        <div class="max-w-5xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold mb-4">Contáctanos Hoy Mismo</h2>
                <p class="text-lg text-blue-100">Estamos listos para atenderte. Envíanos un mensaje o llámanos.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">
                <div>
                    <h3 class="text-2xl font-bold mb-6">Nuestra Ubicación</h3>
                    <p class="text-blue-100 mb-4">Av. Central 456, Colonia Médica, Ciudad Salud, CP 12345</p>
                    <div class="flex items-center mb-3">
                        <i class="fas fa-phone-alt mr-3 text-blue-300"></i>
                        <a href="tel:+525511223344" class="text-white hover:underline">+52 (55) 1122 3344</a>
                    </div>
                    <div class="flex items-center mb-3">
                        <i class="fas fa-envelope mr-3 text-blue-300"></i>
                        <a href="mailto:info@saludintegral.com" class="text-white hover:underline">info@saludintegral.com</a>
                    </div>
                    <div class="h-64 bg-gray-200 rounded-lg overflow-hidden mt-8 shadow-inner">
                        <iframe src="https://www.google.com/maps/embed?pb=!4v1767905182907!6m8!1m7!1s9KK4b5vH2qTAR4IePnSOKg!2m2!1d14.9454225036775!2d-91.11233395348901!3f29.38318095988052!4f-14.969841898959999!5f0.7820865974627469" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
                <div>
                    <h3 class="text-2xl font-bold mb-6">Agenda tu Próxima Consulta</h3>
                    <form action="#" class="space-y-4">
                        <input type="text" placeholder="Tu Nombre Completo" class="w-full p-3 rounded-lg bg-blue-700 border border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 placeholder-blue-200 text-white">
                        <input type="email" placeholder="Tu Correo Electrónico" class="w-full p-3 rounded-lg bg-blue-700 border border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 placeholder-blue-200 text-white">
                        <input type="tel" placeholder="Número de Teléfono" class="w-full p-3 rounded-lg bg-blue-700 border border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 placeholder-blue-200 text-white">
                        <select class="w-full p-3 rounded-lg bg-blue-700 border border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 text-blue-200">
                            <option value="">Selecciona una Especialidad</option>
                            <option value="general">Medicina General</option>
                            <option value="cardiologia">Cardiología</option>
                            <option value="pediatria">Pediatría</option>
                            <option value="odontologia">Odontología</option>
                        </select>
                        <input type="date" class="w-full p-3 rounded-lg bg-blue-700 border border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 text-blue-200">
                        <textarea placeholder="Cuéntanos brevemente el motivo de tu visita" rows="4" class="w-full p-3 rounded-lg bg-blue-700 border border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 placeholder-blue-200 text-white"></textarea>
                        <button type="submit" class="w-full bg-white text-blue-800 font-bold py-3 rounded-lg hover:bg-gray-100 transition-all duration-300 shadow-md">Enviar Solicitud</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-gray-400 py-10 text-center">
        <div class="max-w-7xl mx-auto px-4">
            <p class="mb-2">&copy; 2026 Clínica Avanzada SaludIntegral. Todos los derechos reservados.</p>
            <div class="flex justify-center space-x-4 text-xl">
                <a href="#" class="hover:text-white"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="hover:text-white"><i class="fab fa-twitter"></i></a>
                <a href="#" class="hover:text-white"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Inicializa Swiper
        const swiper = new Swiper('.swiper-container', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    </script>

</body>
</html>