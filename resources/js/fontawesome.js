// resources/js/fontawesome.js

import { library } from '@fortawesome/fontawesome-svg-core';
import { faUser, faCalendarAlt, faCog, faGauge, faRightFromBracket, faBusinessTime } from '@fortawesome/free-solid-svg-icons'; // import the icons you need
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';


// Add icons to the library
library.add(faUser, faCalendarAlt, faCog, faGauge, faRightFromBracket, faBusinessTime);

// Export FontAwesomeIcon for use in Vue components
export default FontAwesomeIcon;
