/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/dex.css';

// start the Stimulus application
import './bootstrap';

let offset = 20;
const charactersContainer = document.querySelector('.characters');
const defaultBackground = 'https://www.serieously.com/app/uploads/2021/07/harry-potter-choixpeau-magique-poudlard.jpg';

const addHoverStyles = (element, styles) => {
    element.addEventListener('mouseover', () => {
        Object.assign(element.style, styles);
    });

    element.addEventListener('mouseout', () => {
        Object.keys(styles).forEach(styleKey => {
            element.style[styleKey] = '';
        });
    });
}

document.getElementById('see-more').addEventListener('click', async () => {
    const response = await fetch(`/dex/load-more/${offset}`);
    const characters = await response.json();

    characters.forEach(character => {
        const characterItem = document.createElement('div');
        characterItem.classList.add('character-item');
        characterItem.id = `character-${character.id}`;

        const characterPicture = document.createElement('div');
        characterPicture.classList.add('character-picture');
        characterPicture.style.backgroundImage = `url('${character.picture ? character.picture : defaultBackground}')`;
        characterPicture.style.backgroundSize = 'cover';
        characterPicture.style.backgroundRepeat = 'no-repeat';
        characterPicture.style.backgroundPosition = 'center center';

        const characterInsight = document.createElement('div');
        characterInsight.classList.add('character-insight');

        const characterName = document.createElement('p');
        characterName.classList.add('character-name', 'badge', `badge-${character.house ? character.house : 'None'}`);
        characterName.textContent = `${character.firstName} ${character.lastName ? character.lastName : ''}`;

        characterInsight.appendChild(characterName);
        characterItem.appendChild(characterPicture);
        characterItem.appendChild(characterInsight);
        charactersContainer.appendChild(characterItem);

        const pictureDiv = document.createElement('div');
        pictureDiv.style.backgroundImage = `url('${character.picture ? character.picture : defaultBackground}')`;
        characterPicture.appendChild(pictureDiv);

        // Ajoutez les styles en hover
        addHoverStyles(pictureDiv, {
            transform: 'scale(1.2)',
            filter: 'brightness(0.5)',
        });
    });

    offset += 20;
})

const searchbar = document.getElementById('searchbar');
searchbar.addEventListener('input', () => {
    const searchTerm = searchbar.value.toLowerCase();
    const characterItems = document.querySelectorAll('.character-item');

    characterItems.forEach(item => {
        const nameBadge = item.querySelector('.character-name');
        const name = nameBadge.textContent.toLowerCase();

        if (name.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});
