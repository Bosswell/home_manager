<?php


namespace App\Exam;


class QuestionSnippetNormalizer
{
    public function normalize(array $snippet): QuestionSnippet
    {
        return new QuestionSnippet(
            (int)$snippet['nbOptions'],
            array_map('intval', explode(',', $snippet['correctOptions'])),
        );
    }

    /**
     * @return QuestionSnippet[]
     */
    public function normalizeArray(array $questionsSnippets): array
    {
        $snippets = [];

        foreach ($questionsSnippets as $snippet) {
            if (
                !key_exists('questionId', $snippet) ||
                !key_exists('correctOptions', $snippet) ||
                !key_exists('nbOptions', $snippet)
            ) {
                throw new \InvalidArgumentException('Invalid exam snippet signature');
            }

            $snippets[(int)$snippet['questionId']] = $this->normalize($snippet);
        }

        return $snippets;
    }
}