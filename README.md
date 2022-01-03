# Covid Case Count

Covid Case Count(CCC) is a progressive web app that will push the covid case counts of the places you have subscribed to as notifications to your phone.

You can use the app by going to [Covid Case Count](https://apps.binnyva.com/ccc/) website. Login using your Google account and subcribed to the places you want informaiton about.

## Installation 

The app can be used without manually installing it - just go to the [CCC Website](https://apps.binnyva.com/ccc/). Install it only if you wish to study or modify it. 

This repository has both the frontend(Vue 3, NPM) and the backend(PHP, [iFrame](https://github.com/binnyva/iframe), Composer). They'll have to be installed seprerately.

### Frontend

```bash 
  cd Frontend
  npm install
```
    
### Backend / API

```bash
cd ../Backend
composer install

```

## Development

Run a development server using this command

```bash
npm run serve
```

Then visit [http://localhost:8080/covid-case-count/](http://localhost:8080/covid-case-count/) . Note the `covid-case-count` directory in the URL. It will NOT work without that part.

## Deployment

This repository has both the frontend(Vue 3, NPM) and the backend(PHP, Composer). You'll have to deploy it seprerately.

### Frontend

```bash
npm run build
```

