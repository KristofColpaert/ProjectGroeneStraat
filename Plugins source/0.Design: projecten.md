# Projecten weergeven voor designers

## Data van een project

Projecten hebben:
* Title (default WordPress)
* Content (default WordPress)
* Featured image (default WordPress)
* _locationStreet (via get_post_meta())
* _locationZipcode (via get_post_meta())
* _locationCity (via get_post_meta())

## Posts van projecten

Posts binnen een project onderscheiden zich van algemene posts doordat:
* Ze toegevoegd zijn aan de categorie **Projectartikels**.
* Ze een _projectParentId hebben dat het ID bevat van het project waaraan ze gelinkt zijn.
