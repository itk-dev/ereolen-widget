// @see https://codeburst.io/dependency-injection-with-vue-js-f6b44a0dae6d
import Vue from 'vue'
import VueI18n from 'vue-i18n'

Vue.use(VueI18n)

const messages = {
    da: {
        '(no title)': '(ingen titel)',
        'Add all materials to carousel': 'Tilføj alle',
        'Add manually': 'Tilføj materialer enkeltvis',
        'Author': 'Forfatter | Forfattere',
        'Build your carousel': 'Byg din eReolen-materialekarrusel',
        'Click on a material to add it to the carousel': 'Klik på et materiale for at tilføje det til karrusellen',
        'Click on a material to remove it from the carousel': 'Klik på et materiale for at fjerne det fra karrusellen',
        'Copy': 'Kopier',
        'Enter author, title, isbn or publisher': 'Skriv forfatter, titel, isbn eller forlag',
        'Example: {ereolen_searchUrl}/dennis': 'Eksempel: {ereolen_searchUrl}/dennis',
        'HTML': 'HTML',
        'Insert this code on your website to display the widget.': 'Indsæt denne kode på dit website for at vise din widget.',
        'Do a search on {ereolen_searchLink} and paste the url here': 'Lav en søgning på {ereolen_searchLink} og indsæt urlen i feltet her.',
        'Materials in the carousel': 'Materialer i karrusellen',
        'Perform a search on {ereolen_searchLink} and copy the url in the address bar. Then paste it here.': 'Lav en søgning på {ereolen_searchLink} og kopier urlen fra adresselinjen. Indsæt den her.',
        'Preview will update when you add or remove materials': 'Forhåndsvisningen opdateres når du tilføjer eller fjerner materialer',
        'Remove all materials from carousel': 'Fjern alle materialer',
        'Save widget': 'Gem widget',
        'Update widget': 'Opdater widget',
        'Search for materials you want to show in the carousel': 'Søg efter materialet du vil vise i karrusellen.',
        'The search will return up to 10 results. If the material you are searching for does not appear then please try to add another keyword.': 'Der vises op til 10 resultater. Hvis materialet du leder efter ikke er iblandt disse, så prøve at tilføje endnu et søgeord.',
        'Search result': 'Søgeresultat',
        'The widget title is displayed as a heading in the widgeten': 'Titlen vises som overskrift i widgeten',
        'URL': 'Url',
        'Use search from eReolen': 'Tilføj materialer fra søgning på eReolen',
        'Widget color': 'Farve',
        'Widget content': 'Indhold',
        'Widget display settings': 'Udseende',
        'Widget embed code': 'Indlejringskode',
        'Widget preview': 'Forhåndsvisning',
        'Widget size': 'Størrelse',
        'Widget title': 'Titel',
        'Copied': 'Kopieret',
        'Embed code copied': 'Koden er kopieret',
        'Press Ctrl+C to copy.': 'Brug Ctrl+C for at kopiere',
        'Fx New titles': 'F.eks.: Nyeste titler',
        'Choose how to add content to the widget carousel': 'Vælg hvordan du vil tilføje indhold til karrusellen.',
        'Build widget': 'Byg widget',
        'How to': 'Vejledninger',
        'About widgets': 'Om brug af widgets',
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
