export let ischeckValid = true

export function checkValidity(input) {
    let check = input.validity
    let isGoodInput = !check.badInput
    let isPatternCorrect = !check.patternMismatch
    let isRangeCorrect = !check.rangeOverflow && !check.rangeUnderflow
    let isStepCorrect = !check.stepMismatch
    let isLengthCorrect = !check.tooLong && !check.tooShort
    let isValid = check.valid
    let isValueThere = !check.valueMissing


    return isGoodInput && isPatternCorrect && isRangeCorrect && isStepCorrect && isLengthCorrect && isValid && isValueThere && input.value.length > 0


}


