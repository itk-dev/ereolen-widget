// @see https://codeburst.io/dependency-injection-with-vue-js-f6b44a0dae6d
import Vue from 'vue'
import VueI18n from 'vue-i18n'

Vue.use(VueI18n)

const messages = {
    da: {
        '(no title)': '(ingen titel)',
        'Add all materials to carousel': 'Tilføj alle',
        'Add manually': 'Tilføj manuelt',
        'Author': 'Forfatter | Forfattere',
        'Build your carousel': 'Byg din eReolen-materialekarrusel',
        'Click on a material to add it to the carousel': 'Klik for at tilføje',
        'Click on a material to remove it from the carousel': 'Klik for at fjerne',
        'Copy': 'Kopier',
        'Enter author, title, isbn or publisher': 'Skriv forfatter, titel, isbn eller forlag',
        'Example: https://ereolen.dk/search/ting/jussi%20adler': 'Eksempel: https://ereolen.dk/search/ting/jussi%20adler',
        'HTML': 'HTML',
        'Insert this code on your website to display the widget.': 'Indsæt denne kode på dit website for at vise din widget.',
        'Insert url from eReolen': 'Indsæt url fra eReolen',
        'Materials in the carousel': 'Materialer i karrusellen',
        'Perform a search on eReolen and copy the url in the address bar. Then paste it here.': 'Lav en søgning på <a target="_blank" href="{ereolen_url}">{ereolen_name}</a> og kopier urlen fra adresselinjen. Indsæt den her.',
        'Preview will update when you add or remove materials': 'Forhåndsvisningen opdateres når du tilføjer eller fjerner materialer',
        'Remove all materials from carousel': 'Fjern alle materialer',
        'Save widget': 'Gem widget',
        'Update widget': 'Opdater widget',
        'Search for materials you want to show in the carousel': 'Søg efter bøger du vil vise i karrusellen',
        'Search result': 'Søgeresultat',
        'The widget title is displayed as a heading in the widgeten': 'Titlen vises som overskrift i widgeten',
        'URL': 'Url',
        'Use search from eReolen': 'Brug søgning fra eReolen',
        'Widget color': 'Farve',
        'Widget content': 'Indhold',
        'Widget display settings': 'Udseende',
        'Widget embed code': 'Indlejringskode',
        'Widget preview': 'Forhåndsvisning',
        'Widget size': 'Størrelse',
        'Widget title': 'Titel',
        'Copied': 'Kopieret',
        'Press CTRL+C to copy.': 'Brug CRTL+C for at kopiere',
        theme: {
            dark: 'Mørk',
            light: 'Lys'
        }
    },
    en: {
    }
}

// http://www.ecma-international.org/ecma-402/2.0/#sec-intl-datetimeformat-constructor
const dateTimeFormats = {
    da: {
        short: {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        },
        long: {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: 'numeric',
            minute: 'numeric'
        }
    }
}

export default function makeI18n (locale) {
    return new VueI18n({
        locale,
        messages,
        dateTimeFormats
    })
}
