<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaludIntegral | Excelencia Médica y Cuidado Avanzado</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-effect { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); }
        .swiper-container { height: 75vh; }
        .medical-gradient { background: linear-gradient(135deg, #0f172a 0%, #1e40af 100%); }
        .text-gradient { background: linear-gradient(to right, #2563eb, #3b82f6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">

    <nav class="bg-white/80 glass-effect sticky top-0 z-[100] border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="text-2xl font-bold flex items-center tracking-tight text-slate-800">
                <span class="bg-blue-600 p-2 rounded-lg mr-2 text-white">
                    <i class="fas fa-notes-medical"></i>
                </span>
                Salud<span class="text-blue-600">Integral</span>
            </div>
            <div class="hidden lg:flex items-center space-x-10 font-semibold text-sm uppercase tracking-wider">
                <a href="#inicio" class="text-slate-600 hover:text-blue-600 transition">Inicio</a>
                <a href="#especialidades" class="text-slate-600 hover:text-blue-600 transition">Servicios</a>
                <a href="#testimonios" class="text-slate-600 hover:text-blue-600 transition">Pacientes</a>
                <a href="#faq" class="text-slate-600 hover:text-blue-600 transition">Dudas</a>
            </div>
            <div class="flex items-center space-x-4">
                <a href="#contacto" class="bg-blue-600 text-white px-6 py-2.5 rounded-full font-bold shadow-lg hover:bg-blue-700 transition transform hover:-translate-y-0.5">
                    Agendar Cita
                </a>
            </div>
        </div>
    </nav>

    <section id="inicio" class="relative overflow-hidden">
        <div class="swiper swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide relative flex items-center">
                    <img src="https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&w=1920&q=80" class="absolute inset-0 w-full h-full object-cover" alt="Clínica">
                    <div class="absolute inset-0 bg-slate-900/40"></div>
                    <div class="container mx-auto px-6 relative z-10">
                        <div class="max-w-2xl text-white">
                            <span class="inline-block px-4 py-1 rounded-full bg-blue-600/20 border border-blue-400/30 backdrop-blur-md mb-4 text-sm font-bold uppercase tracking-widest">Tecnología Médica de Élite</span>
                            <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">Su salud merece el mejor cuidado médico.</h1>
                            <p class="text-xl text-slate-100 mb-8 font-light">Líderes en atención personalizada con tecnología de última generación para diagnósticos precisos.</p>
                            <div class="flex space-x-4">
                                <a href="#contacto" class="bg-white text-blue-700 px-8 py-4 rounded-xl font-bold hover:bg-blue-50 transition shadow-xl">Comenzar Ahora</a>
                                <button class="flex items-center space-x-2 text-white font-bold hover:text-blue-200 transition" id="btnVerVideo">
                                    <span class="w-12 h-12 flex items-center justify-center rounded-full border-2 border-white/50"><i class="fas fa-play"></i></span>
                                    <span>Ver Video</span>
                                </button>
                                <div id="videoModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-black/80 p-4">
                                    <div class="relative w-full max-w-4xl aspect-video bg-black rounded-lg overflow-hidden">
                                        <button id="cerrarModal" class="absolute top-4 right-4 text-white text-3xl z-10 hover:text-blue-400 transition">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        
                                        <iframe id="videoPlayer" 
                                                class="w-full h-full" 
                                                src="" 
                                                title="YouTube video player" 
                                                frameborder="0" 
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                                allowfullscreen>
                                        </iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <div class="relative z-20 -mt-16 max-w-6xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-0 rounded-3xl overflow-hidden shadow-2xl shadow-blue-900/10">
            <div class="bg-white p-10 flex items-center space-x-6 border-r border-slate-50">
                <i class="fas fa-user-check text-4xl text-blue-600"></i>
                <div>
                    <h4 class="text-3xl font-bold text-slate-800">15k+</h4>
                    <p class="text-slate-500 font-medium">Pacientes Felices</p>
                </div>
            </div>
            <div class="bg-white p-10 flex items-center space-x-6 border-r border-slate-50">
                <i class="fas fa-award text-4xl text-blue-600"></i>
                <div>
                    <h4 class="text-3xl font-bold text-slate-800">25+</h4>
                    <p class="text-slate-500 font-medium">Años de Excelencia</p>
                </div>
            </div>
            <div class="bg-white p-10 flex items-center space-x-6">
                <i class="fas fa-microscope text-4xl text-blue-600"></i>
                <div>
                    <h4 class="text-3xl font-bold text-slate-800">40+</h4>
                    <p class="text-slate-500 font-medium">Especialistas</p>
                </div>
            </div>
        </div>
    </div>

    <section id="especialidades" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16">
                <div class="max-w-xl">
                    <h2 class="text-blue-600 font-bold uppercase tracking-widest text-sm mb-3">Nuestros Servicios</h2>
                    <h3 class="text-4xl md:text-5xl font-bold text-slate-900">Especialidades diseñadas para su bienestar integral.</h3>
                </div>
                <a href="#" class="mt-6 md:mt-0 text-blue-600 font-bold flex items-center hover:underline">
                    Ver todas las áreas <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="group bg-white p-10 rounded-[2rem] shadow-sm border border-slate-100 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-900/5 transition-all duration-500">
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 text-3xl mb-8 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-4">Cardiología</h4>
                    <p class="text-slate-500 leading-relaxed mb-6">Prevención, diagnóstico y tratamiento avanzado de patologías cardiovasculares.</p>
                    <a href="#" class="text-sm font-bold text-slate-400 group-hover:text-blue-600 transition">Saber más +</a>
                </div>
                <div class="group bg-blue-600 p-10 rounded-[2rem] shadow-xl shadow-blue-900/20 text-white transform lg:-translate-y-4">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-white text-3xl mb-8">
                        <i class="fas fa-baby"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-4">Pediatría</h4>
                    <p class="text-blue-100 leading-relaxed mb-6">Acompañamos el crecimiento de sus hijos con el cuidado más cálido y profesional.</p>
                    <a href="#" class="text-sm font-bold text-white/80 hover:text-white transition">Saber más +</a>
                </div>
                <div class="group bg-white p-10 rounded-[2rem] shadow-sm border border-slate-100 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-900/5 transition-all duration-500">
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 text-3xl mb-8 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-4">Neurología</h4>
                    <p class="text-slate-500 leading-relaxed mb-6">Especialistas en el sistema nervioso y tratamientos de alta complejidad.</p>
                    <a href="#" class="text-sm font-bold text-slate-400 group-hover:text-blue-600 transition">Saber más +</a>
                </div>
                <div class="group bg-white p-10 rounded-[2rem] shadow-sm border border-slate-100 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-900/5 transition-all duration-500">
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 text-3xl mb-8 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i class="fas fa-dna"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-4">Laboratorio</h4>
                    <p class="text-slate-500 leading-relaxed mb-6">Resultados precisos con los más altos estándares de bioseguridad internacional.</p>
                    <a href="#" class="text-sm font-bold text-slate-400 group-hover:text-blue-600 transition">Saber más +</a>
                </div>
            </div>
        </div>
    </section>

    <section id="equipo" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-blue-600 font-bold uppercase tracking-widest text-sm mb-3">Nuestra Facultad</h2>
            <h3 class="text-4xl font-bold text-slate-900 mb-16">Conozca a nuestros expertos</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-12">
                <div class="group">
                    <div class="relative overflow-hidden rounded-[2.5rem] mb-6 shadow-lg">
                        <img src="https://images.unsplash.com/photo-1559839734-2b716b1772aa?q=80&w=800&auto=format&fit=crop" class="w-full h-96 object-cover group-hover:scale-110 transition duration-700" alt="Doctor">
                        <div class="absolute bottom-4 left-4 right-4 bg-white/90 backdrop-blur p-6 rounded-3xl translate-y-2 group-hover:translate-y-0 transition duration-500">
                            <h4 class="text-xl font-bold text-slate-900">Dra. Elena Martínez</h4>
                            <p class="text-blue-600 font-semibold">Jefa de Cardiología</p>
                        </div>
                    </div>
                </div>
                <div class="group">
                    <div class="relative overflow-hidden rounded-[2.5rem] mb-6 shadow-lg">
                        <img src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?q=80&w=800&auto=format&fit=crop" class="w-full h-96 object-cover group-hover:scale-110 transition duration-700" alt="Doctor">
                        <div class="absolute bottom-4 left-4 right-4 bg-white/90 backdrop-blur p-6 rounded-3xl translate-y-2 group-hover:translate-y-0 transition duration-500">
                            <h4 class="text-xl font-bold text-slate-900">Dr. Javier López</h4>
                            <p class="text-blue-600 font-semibold">Especialista en Cirugía</p>
                        </div>
                    </div>
                </div>
                <div class="group">
                    <div class="relative overflow-hidden rounded-[2.5rem] mb-6 shadow-lg">
                        <img src="https://images.unsplash.com/photo-1594824476967-ce62255757d7?q=80&w=800&auto=format&fit=crop" class="w-full h-96 object-cover group-hover:scale-110 transition duration-700" alt="Doctor">
                        <div class="absolute bottom-4 left-4 right-4 bg-white/90 backdrop-blur p-6 rounded-3xl translate-y-2 group-hover:translate-y-0 transition duration-500">
                            <h4 class="text-xl font-bold text-slate-900">Dra. Sarah Collins</h4>
                            <p class="text-blue-600 font-semibold">Medicina Preventiva</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contacto" class="py-24 bg-slate-900 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-blue-600/10 skew-x-12 transform translate-x-20"></div>
        
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="bg-white rounded-[3rem] shadow-2xl overflow-hidden flex flex-col lg:flex-row">
                <div class="lg:w-1/3 bg-slate-50 p-12 md:p-16">
                    <h3 class="text-3xl font-bold mb-8 text-slate-900">Canales de Atención</h3>
                    <div class="space-y-8">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-white shadow-sm rounded-xl flex items-center justify-center text-blue-600 mr-4 shrink-0">
                                <i class="fas fa-phone-volume text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-1">Central Médica</p>
                                <p class="text-lg font-bold text-slate-800">+502 1234 0000</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-white shadow-sm rounded-xl flex items-center justify-center text-blue-600 mr-4 shrink-0">
                                <i class="fas fa-map-marked-alt text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-1">Ubicación</p>
                                <p class="text-lg font-bold text-slate-800">Santa Catarina Pinula zona 10</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-12 p-8 bg-blue-600 rounded-3xl text-white">
                        <h4 class="font-bold mb-2">Horarios de Urgencias</h4>
                        <p class="text-blue-100 text-sm opacity-80 mb-4">Contamos con atención de emergencias las 24 horas, los 7 días de la semana.</p>
                        <a href="tel:911" class="inline-block bg-white text-blue-600 px-6 py-2 rounded-xl font-bold text-sm">Llamar Ahora</a>
                    </div>
                </div>

                <div class="lg:w-2/3 p-12 md:p-16">
                    <h3 class="text-3xl font-bold mb-8 text-slate-900">Agende su Consulta</h3>
                    <form action="#" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Nombre Completo</label>
                            <input type="text" placeholder="Ej. Juan Pérez" class="w-full p-4 rounded-2xl bg-slate-50 border border-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:bg-white transition">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Correo Electrónico</label>
                            <input type="email" placeholder="juan@ejemplo.com" class="w-full p-4 rounded-2xl bg-slate-50 border border-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:bg-white transition">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Especialidad</label>
                            <select class="w-full p-4 rounded-2xl bg-slate-50 border border-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:bg-white transition">
                                <option>Seleccione una opción</option>
                                <option>Cardiología</option>
                                <option>Medicina General</option>
                                <option>Pediatría</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Fecha Preferente</label>
                            <input type="date" class="w-full p-4 rounded-2xl bg-slate-50 border border-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:bg-white transition text-slate-500">
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Motivo de consulta</label>
                            <textarea rows="4" placeholder="Describa brevemente sus síntomas..." class="w-full p-4 rounded-2xl bg-slate-50 border border-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:bg-white transition"></textarea>
                        </div>
                        <button class="md:col-span-2 bg-blue-600 text-white font-bold py-5 rounded-2xl hover:bg-blue-700 transition shadow-xl shadow-blue-200 uppercase tracking-widest text-sm">Enviar Solicitud de Cita</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <section id="testimonios" class="py-24 bg-blue-600 overflow-hidden relative">
        <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
            <i class="fas fa-quote-right text-[20rem] absolute -bottom-20 -right-10 text-white"></i>
        </div>
        
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-white/80 font-bold uppercase tracking-widest text-sm mb-3">La voz de nuestros pacientes</h2>
                <h3 class="text-4xl font-bold text-white">Experiencias que nos motivan</h3>
            </div>

            <div class="swiper swiper-testimonials pb-12">
                <div class="swiper-wrapper">
                    <div class="swiper-slide h-auto">
                        <div class="bg-white p-10 rounded-[2.5rem] shadow-xl h-full flex flex-col justify-between">
                            <div class="mb-6 text-blue-600 text-2xl">
                                <i class="fas fa-quote-left"></i>
                            </div>
                            <p class="text-slate-600 italic text-lg mb-8 leading-relaxed">
                                "La atención en SaludIntegral superó mis expectativas. El equipo de cardiología no solo es profesional, sino profundamente humano. Me sentí seguro en todo momento."
                            </p>
                            <div class="flex items-center">
                                <img src="https://i.pravatar.cc/150?u=1" class="w-14 h-14 rounded-full border-2 border-blue-100 mr-4" alt="Paciente">
                                <div>
                                    <h4 class="font-bold text-slate-900">Carlos Méndez</h4>
                                    <p class="text-sm text-slate-400">Paciente de Cardiología</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide h-auto">
                        <div class="bg-white p-10 rounded-[2.5rem] shadow-xl h-full flex flex-col justify-between">
                            <div class="mb-6 text-blue-600 text-2xl">
                                <i class="fas fa-quote-left"></i>
                            </div>
                            <p class="text-slate-600 italic text-lg mb-8 leading-relaxed">
                                "Llevo a mis hijos a pediatría aquí desde hace 5 años. El Dr. Pérez tiene una paciencia increíble. Es una clínica que realmente cuida a la familia."
                            </p>
                            <div class="flex items-center">
                                <img src="https://i.pravatar.cc/150?u=2" class="w-14 h-14 rounded-full border-2 border-blue-100 mr-4" alt="Paciente">
                                <div>
                                    <h4 class="font-bold text-slate-900">Mariana Solís</h4>
                                    <p class="text-sm text-slate-400">Madre de familia</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination !-bottom-2"></div>
            </div>
        </div>
    </section>

    <section id="faq" class="py-24 bg-white">
        <div class="max-w-4xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-blue-600 font-bold uppercase tracking-widest text-sm mb-3">Centro de Ayuda</h2>
                <h3 class="text-4xl font-bold text-slate-900">Preguntas Frecuentes</h3>
            </div>

            <div class="space-y-4">
                <div class="faq-item group border border-slate-100 rounded-3xl bg-slate-50 transition-all duration-300">
                    <button class="w-full flex justify-between items-center p-6 text-left focus:outline-none" onclick="toggleFaq(this)">
                        <span class="font-bold text-slate-800 text-lg">¿Cómo puedo agendar una cita por primera vez?</span>
                        <i class="fas fa-chevron-down faq-icon transition-transform duration-300 text-blue-600"></i>
                    </button>
                    <div class="faq-content">
                        <p class="px-6 pb-6 text-slate-600">Puede agendarla a través de nuestro formulario en línea, llamando a nuestra central médica o directamente por nuestro chat de WhatsApp habilitado 24/7.</p>
                    </div>
                </div>
                <div class="faq-item group border border-slate-100 rounded-3xl bg-slate-50 transition-all duration-300">
                    <button class="w-full flex justify-between items-center p-6 text-left focus:outline-none" onclick="toggleFaq(this)">
                        <span class="font-bold text-slate-800 text-lg">¿Aceptan seguros médicos internacionales?</span>
                        <i class="fas fa-chevron-down faq-icon transition-transform duration-300 text-blue-600"></i>
                    </button>
                    <div class="faq-content">
                        <p class="px-6 pb-6 text-slate-600">Sí, trabajamos con las principales aseguradoras nacionales e internacionales. Le recomendamos contactarnos para verificar su póliza específica.</p>
                    </div>
                </div>
                <div class="faq-item group border border-slate-100 rounded-3xl bg-slate-50 transition-all duration-300">
                    <button class="w-full flex justify-between items-center p-6 text-left focus:outline-none" onclick="toggleFaq(this)">
                        <span class="font-bold text-slate-800 text-lg">¿Cuentan con servicios de urgencias nocturnas?</span>
                        <i class="fas fa-chevron-down faq-icon transition-transform duration-300 text-blue-600"></i>
                    </button>
                    <div class="faq-content">
                        <p class="px-6 pb-6 text-slate-600">Contamos con una unidad de cuidados intensivos y urgencias operativa las 24 horas del día, todos los días del año.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-slate-50 border-t border-slate-200 pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <div class="md:col-span-1">
                    <div class="text-2xl font-bold flex items-center mb-6 text-slate-800">
                        <span class="bg-blue-600 p-2 rounded-lg mr-2 text-white"><i class="fas fa-notes-medical"></i></span>
                        SaludIntegral
                    </div>
                    <p class="text-slate-500 leading-relaxed">Excelencia médica comprometida con la innovación y el trato humano desde 2001.</p>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 mb-6 uppercase tracking-wider text-sm">Navegación</h4>
                    <ul class="space-y-4 text-slate-500">
                        <li><a href="#" class="hover:text-blue-600 transition">Servicios</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition">Tecnología</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition">Blog Médico</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 mb-6 uppercase tracking-wider text-sm">Legal</h4>
                    <ul class="space-y-4 text-slate-500">
                        <li><a href="#" class="hover:text-blue-600 transition">Privacidad de datos</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition">Términos de servicio</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition">Consentimiento</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 mb-6 uppercase tracking-wider text-sm">Redes Sociales</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-200 pt-8 text-center text-sm text-slate-400 font-medium">
                &copy; 2026 SaludIntegral Medical Group. Todos los derechos reservados.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        new Swiper('.swiper-container', {
            loop: true,
            effect: 'fade',
            autoplay: { delay: 6000 },
            pagination: { el: '.swiper-pagination', clickable: true },
        });
        // Swiper para Testimonios
        new Swiper('.swiper-testimonials', {
            loop: true,
            slidesPerView: 1,
            spaceBetween: 30,
            autoplay: { delay: 5000 },
            pagination: { el: '.swiper-pagination', clickable: true },
            breakpoints: {
                768: { slidesPerView: 2 }
            }
        });

        // Lógica de Acordeón FAQ
        function toggleFaq(button) {
            const item = button.parentElement;
            const isActive = item.classList.contains('active');
            
            // Cerrar todos los demás
            document.querySelectorAll('.faq-item').forEach(i => {
                i.classList.remove('active', 'bg-white', 'shadow-md');
                i.classList.add('bg-slate-50');
            });

            if (!isActive) {
                item.classList.add('active', 'bg-white', 'shadow-md');
                item.classList.remove('bg-slate-50');
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('btnVerVideo'); //
            const modal = document.getElementById('videoModal'); //
            const cerrar = document.getElementById('cerrarModal'); //
            const iframe = document.getElementById('videoPlayer');

            // REEMPLAZA ESTE ID por el de tu video (lo que va después de v=)
            const videoID = "MT3ZHLBUGnY"; 
            const youtubeURL = `https://www.youtube.com/embed/${videoID}?autoplay=1&rel=0`;

            // Abrir modal y cargar video
            btn.addEventListener('click', () => {
                iframe.src = youtubeURL; // Al asignar el SRC con autoplay=1, el video inicia solo
                modal.classList.remove('hidden');
            });

            // Función para cerrar y "destruir" el video
            const cerrarVideo = () => {
                modal.classList.add('hidden');
                iframe.src = ""; // Al vaciar el SRC, el video se detiene por completo
            };

            cerrar.addEventListener('click', cerrarVideo);

            // Cerrar al hacer clic fuera
            modal.addEventListener('click', (e) => {
                if (e.target === modal) cerrarVideo();
            });

            // Cerrar con tecla ESC
            document.addEventListener('keydown', (e) => {
                if (e.key === "Escape") cerrarVideo();
            });
        });
    </script>
</body>
</html>