import { extend, localize, configure } from 'vee-validate';
configure({
    classes: {
        valid: 'is-valid',
        invalid: 'is-invalid',
        dirty: ['is-dirty', 'is-dirty'], // multiple classes per flag!
        // ...
    }
})
import es from 'vee-validate/dist/locale/es.json';
import * as rules from 'vee-validate/dist/rules';
// validaciones customs
extend('lnum',  {
    validate(value, { min, max }) {
        const length = parseFloat(value);
        return length >= parseFloat(min) && length <= parseFloat(max);
    },
    message: 'Numero Invalido',
    params: ['min','max']
});


Object.keys(rules).forEach(rule => {
    extend(rule, rules[rule]);
});
localize('es', es);

