export const strings = {
    badges: {
        admin: "Admin",
        running: "Running",
        pending: "Pending",
        started: "Started",
    },
    buttons: {
        addProject: "New Hosting Project",
        addBackup: "Start Backup",
        addDeploy: "Start Deploy",
        addRestore: "Restore",
        addEnvironment: "Add Environment",
        addBranchEnvironment: "Add {0} Environment",
    },
    toasts: {
        addDeploy: "Added a deploy",
        addInstall: "Installing Concrete",
        addBackup: "Added a backup",
        addRestore: "Restoring {0}",
    },

    /**
     * Language specific translations
     */
    lang: {
        es: {
            badges: {
                admin: "Administración",
                running: "Corriendo",
                pending: "Pendiente",
                started: "Empezado",
            },
            buttons: {
                addProject: "Nuevo proyecto de alojamiento",
                addBackup: "Iniciar copia de seguridad",
                addDeploy: "Iniciar desplegar",
                addRestore: "Restaurar",
                addEnvironment: "Agregar entorno",
                addBranchEnvironment: "Agregar {0} entorno",
            },
            toasts: {
                addDeploy: "Añadida una desplegar",
                addInstall: "Instalando Concrete",
                addBackup: "Se agregó una copia de seguridad",
                addRestore: "Restaurando {0}",
            },
        },
        de: {
            badges: {
                admin: "Administrator",
                running: "Laufen",
                pending: "Ausstehend",
                started: "Gestartet",
            },
            buttons: {
                addProject: "Neues Hosting-Projekt",
                addBackup: "Sicherung starten",
                addDeploy: "Bereitstellung starten",
                addRestore: "Wiederherstellen",
                addEnvironment: "Umgebung hinzufügen",
                addBranchEnvironment: "{0} Umgebung hinzufügen",
            },
            toasts: {
                addDeploy: "Bereitstellung hinzugefügt",
                addInstall: "Beton einbauen",
                addBackup: "Backup hinzugefügt",
                addRestore: "Wiederherstellung von {0}",
            },
        }
    },
}

// Pick the proper language and replace strings
const lang = navigator.language ? navigator.language.trim().toLowerCase() : 'en'
if (lang !== 'en' && typeof strings.lang[lang] !== 'undefined') {
    for (const key in strings.lang[lang]) {
        if (!strings.hasOwnProperty(key)) {
            continue
        }

        for (const stringKey in strings.lang[lang][key]) {
            if (!strings.lang[lang][key].hasOwnProperty(stringKey)) {
                continue
            }

            strings[key][stringKey] = strings.lang[lang][key][stringKey]
        }
    }
}

// Add functions
strings.t = (string, ...args) => {
    return string.replace(/(\{\d+\})/g, function(a) {
        return args[+(a.substr(1, a.length - 2)) || 0];
    });
}

strings.stats = (lang) => {
    let total = {}
    let langTotal = {}
    let coverage = {}

    const language = strings.lang[lang] ? strings.lang[lang] : {}
    for (const group in strings) {
        if (group === 'lang' || !strings.hasOwnProperty(group) || typeof strings[group] !== "object") {
            continue;
        }

        coverage[group] = 0

        for (const key in strings[group]) {
            total[group] = total[group] ? total[group] + 1 : 1
            if (typeof language[group] !== 'undefined' && typeof language[group][key] === 'string') {
                langTotal[group] = langTotal[group] ? langTotal[group] + 1 : 1
            }
        }

        if (!total[group]) {
            coverage[group] = 100;
        } else if (langTotal[group]) {
            coverage[group] = Math.round(langTotal[group] / total[group] * 10000) / 100
        }
    }

    return {
        total,
        langTotal,
        coverage,
    }
}