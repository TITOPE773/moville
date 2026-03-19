// --- DICCIONARIO DE IDIOMAS ---
// Aquí agregas todas las traducciones que necesites
const languages = {
    es: {
        nav: { 
            home: "Inicio", 
            cta: "Solicitar Reparación" 
        },
        services: {
            desc: "Este es un texto de ejemplo que cambiará de idioma."
        }
    },
    en: {
        nav: { 
            home: "Home", 
            cta: "Request Repair" 
        },
        services: {
            desc: "This is an example text that will change language."
        }
    }
};

// --- FUNCIÓN PRINCIPAL ---
function changeLanguage(lang) {
    // 1. Actualizar el estilo de los botones (poner activo el que toca)
    document.querySelectorAll('.lang-btn').forEach(btn => {
        btn.classList.toggle('active', btn.innerText.toLowerCase() === lang);
    });

    // 2. Buscar todos los elementos que tengan el atributo data-section
    const textsToChange = document.querySelectorAll('[data-section]');
    
    // 3. Reemplazar el texto de cada elemento con el diccionario
    textsToChange.forEach(el => {
        const section = el.dataset.section;
        const value = el.dataset.value;
        
        // Verifica si la traducción existe en el diccionario antes de cambiarla
        if (languages[lang][section] && languages[lang][section][value]) {
            el.innerText = languages[lang][section][value];
        }
    });

    // 4. Guardar el idioma elegido en el navegador
    localStorage.setItem('pref_lang', lang);
}

// --- AL CARGAR LA PÁGINA ---
document.addEventListener('DOMContentLoaded', () => {
    // Revisa si ya había un idioma guardado, si no, usa español ('es') por defecto
    const savedLang = localStorage.getItem('pref_lang') || 'es';
    changeLanguage(savedLang);
});