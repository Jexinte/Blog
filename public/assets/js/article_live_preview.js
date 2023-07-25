const title = document.getElementById("title")
const titlePreview = document.getElementById("title-preview")
const shortPhrase = document.getElementById("short-phrase")
const shortPhrasePreview = document.getElementById("short-phrase-preview")
const content = document.getElementById("content");
const contentPreview = document.getElementById("content-preview");
const imagePreview = document.getElementById("img-preview")

const form = document.querySelector("form")
title.addEventListener("change",() => {
  titlePreview.textContent = title.value
})


const displayImagePreview = () => {
const imageFile = document.getElementById('image-file')

  if(imageFile.files.length != 0){
    
    let reader = new FileReader()
    reader.onload = function (e)  {
      imagePreview.src=e.target.result
    }
    reader.readAsDataURL(imageFile.files[0])
  }
  else{
    imagePreview.style.display = "none"
  }

}


shortPhrase.addEventListener("change",() => {
  shortPhrasePreview.textContent = shortPhrase.value
})

content.addEventListener("change",() => {
  contentPreview.textContent = content.value
})



