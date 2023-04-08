const c1 = {
    id: "1",
    parent: null,
}
const c2 = {
    id: "2",
    parent: null,
}
const c3 = {
    id: "3",
    parent: null,
}
const c4 = {
    id: "4",
    parent: 1,
}
const c5 = {
    id: "5",
    parent: 1,
}
const c6 = {
    id: "6",
    parent: 1,
}
const c7 = {
    id: "7",
    parent: 2,
}
const c8 = {
    id: "8",
    parent: 2,
}
const c9 = {
    id: "9",
    parent: 2,
}
const c10 = {
    id: "10",
    parent: 3,
}
const c11 = {
    id: "11",
    parent: 4,
}
const c12 = {
    id: "12",
    parent: 5,
}


let fruits = [c1, c2, c3, c4, c5, c6, c7, c8, c9, c10, c11, c12];

function recurssiveGrab(fruits, parent) {
    for (let i = 0; i < fruits.length(); i++) {
        if (fruits[i].parent == parent) {
            console.log(fruits[i].id + " \n");
            recurssiveGrab(fruits, fruits[i].id)
            fruits[i].parent == -1;
        }
    }
}