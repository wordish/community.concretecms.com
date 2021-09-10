export const strings = {
    toasts: {
        addDeploy: "Added a deploy",
        addBackup: "Added a backup",
        addRestore: "Restoring {0}",
    },
    lang: {
        en: {
        }
    }
}

// Pack all toasts into en
for (let i in strings.toasts) {
    if (!strings.toasts.hasOwnProperty(i)) continue
    strings.lang.en[strings.toasts[i]] = strings.toasts[i]
}

const formatString = function(string) {
    const args = Array.prototype.slice.call(arguments, 1);
    return string.replace(/{(\d+)}/g, function(match, number) {
        return typeof args[number] != 'undefined' ? args[number] : match;
    });
};

/** @todo Make this dynamic */
const currentLang = strings.lang.en
export function translate(string) {
    const args = Array.prototype.slice.call(arguments, 1);
    const langVersion = currentLang[string]
    if (!langVersion) {
        return ''
    }

    return formatString.apply(null, [langVersion, ...args])
}
